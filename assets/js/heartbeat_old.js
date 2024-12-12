/**
 * heartbeat.js
 *
 * Mengelola pengiriman heartbeat secara periodik dan deteksi inaktivitas pengguna.
 * Menggunakan Axios untuk permintaan HTTP dan BroadcastChannel atau alternatif untuk sinkronisasi antar tab.
 * Menggunakan Page Visibility API untuk memastikan hanya satu heartbeat dikirim setiap 30 detik.
 */

(function () {
    // Cek apakah Axios tersedia. Axios digunakan untuk mengirim permintaan HTTP.
    if (typeof axios === 'undefined') {
        console.error('Axios tidak ditemukan. Pastikan untuk menyertakan Axios sebelum heartbeat.js.');
        return; // Menghentikan eksekusi script jika Axios tidak tersedia.
    }

    // Cek apakah SweetAlert2 tersedia. SweetAlert2 digunakan untuk menampilkan dialog.
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 tidak ditemukan. Pastikan untuk menyertakan SweetAlert2 sebelum heartbeat.js.');
        return; // Menghentikan eksekusi script jika SweetAlert2 tidak tersedia.
    }

    // Cek apakah BroadcastChannel tersedia. BroadcastChannel digunakan untuk komunikasi antar tab.
    const isBroadcastChannelSupported = typeof BroadcastChannel !== 'undefined';
    if (!isBroadcastChannelSupported) {
        console.warn('BroadcastChannel tidak didukung oleh browser ini. Menggunakan fallback.');
    }

    // Inisialisasi BroadcastChannel jika didukung, atau null jika tidak.
    // Jika tidak didukung, akan menggunakan fallback berupa Event Listener pada localStorage.
    const channel = isBroadcastChannelSupported ? new BroadcastChannel('heartbeat_channel') : null;

    // Flag untuk menentukan apakah tab ini adalah leader yang mengirim heartbeat.
    // Hanya satu tab yang akan berperan sebagai leader dalam satu waktu.
    let isHeartbeatLeader = false;

    // Variabel untuk menyimpan timer heartbeat.
    let heartbeatTimer = null;

    // Interval heartbeat dalam milidetik (30 detik).
    const heartbeatInterval = 30000;

    // Mendapatkan waktu login pengguna dari localStorage (dalam bentuk timestamp).
    let loginTimestamp = parseInt(localStorage.getItem('login_timestamp'), 10);

    // Jika tidak ada loginTimestamp di localStorage, inisialisasi dengan waktu sekarang.
    if (!loginTimestamp) {
        loginTimestamp = Date.now();
        localStorage.setItem('login_timestamp', loginTimestamp);
    }

    /**
     * Membuat unique identifier untuk setiap tab.
     * Menggunakan kombinasi timestamp dan random number untuk memastikan keunikan.
     */
    const tabId = `${Date.now()}-${Math.floor(Math.random() * 1000000)}`;
    localStorage.setItem('current_tab_id', tabId);

    /**
     * Fungsi untuk menghitung waktu hingga heartbeat berikutnya.
     * Mengembalikan waktu dalam milidetik.
     * @returns {number} Waktu dalam milidetik hingga heartbeat berikutnya.
     */
    function timeUntilNextHeartbeat() {
        const now = Date.now();
        return heartbeatInterval - ((now - loginTimestamp) % heartbeatInterval);
    }

    // Waktu inaktivitas sebelum peringatan (25 menit) dan sebelum auto-logout (30 menit).
    const warningTimeout = 1500000; // 25 menit dalam milidetik.
    const inactivityTimeout = 1800000; // 30 menit dalam milidetik.

    // Timer untuk inaktivitas dan peringatan.
    let inactivityTimer;
    let warningTimer;

    // Variabel untuk mengelola reconnect ketika koneksi terputus.
    let isDisconnected = false;
    let disconnectTimer;
    const disconnectTimeoutDuration = 10000; // 10 detik sebelum auto-logout.

    // Flag untuk menandai apakah halaman sedang di-unload (ditutup atau di-refresh).
    let isUnloading = false;

    // Counter untuk jumlah tab yang terbuka. Ini digunakan untuk mengelola peran leader.
    let tabCount = parseInt(localStorage.getItem('tab_count'), 10) || 0;

    /**
     * Fungsi untuk menambah atau mengurangi tabCount dengan benar.
     * @param {number} delta - Nilai perubahan (positif untuk penambahan, negatif untuk pengurangan).
     */
    function updateTabCount(delta) {
        tabCount += delta;
        tabCount = Math.max(tabCount, 0); // Pastikan tabCount tidak negatif.
        localStorage.setItem('tab_count', tabCount);
        // Kirim pesan perubahan tabCount dengan informasi delta.
        broadcastMessage({ type: 'tab_count_changed', tabCount: tabCount, delta: delta });
    }

    // Tambahkan tabCount ketika tab dibuka.
    updateTabCount(1);

    /**
     * Fungsi untuk mengirim pesan melalui BroadcastChannel atau fallback.
     * @param {Object} message - Pesan yang akan dikirim.
     */
    function broadcastMessage(message) {
        if (isBroadcastChannelSupported && channel) {
            // Kirim pesan melalui BroadcastChannel jika didukung.
            channel.postMessage(message);
        } else {
            // Fallback menggunakan localStorage jika BroadcastChannel tidak didukung.
            localStorage.setItem('heartbeat_message', JSON.stringify({ ...message, timestamp: Date.now() }));
            // Hapus pesan setelah dikirim untuk mencegah duplikasi.
            setTimeout(() => {
                localStorage.removeItem('heartbeat_message');
            }, 0);
        }
    }

    /**
     * Fungsi untuk menangani pesan yang diterima melalui BroadcastChannel atau fallback.
     * Fungsi ini mengatur bagaimana tab merespons pesan yang diterima.
     * @param {Object} message - Pesan yang diterima.
     */
    function setupMessageListener() {
        if (isBroadcastChannelSupported && channel) {
            // Jika BroadcastChannel didukung, tambahkan event listener untuk pesan yang diterima.
            channel.onmessage = function (event) {
                handleMessage(event.data);
            };
        } else {
            // Fallback menggunakan localStorage. Tambahkan event listener untuk perubahan storage.
            window.addEventListener('storage', function (event) {
                if (event.key === 'heartbeat_message' && event.newValue) {
                    const message = JSON.parse(event.newValue);
                    handleMessage(message);
                }
            });
        }
    }

    /**
     * Fungsi untuk menangani pesan yang diterima.
     * Mengatur tindakan berdasarkan jenis pesan yang diterima.
     * @param {Object} message - Pesan yang diterima.
     */
    function handleMessage(message) {
        if (!message || typeof message !== 'object') return;

        switch (message.type) {
            case 'heartbeat_leader_present':
                // Jika ada leader yang aktif dari tab lain, dan tab ini juga leader, resign dari peran leader.
                // Pastikan pesan berasal dari tab lain.
                if (isHeartbeatLeader && message.tabId !== tabId) {
                    resignLeadership();
                }
                break;
            case 'heartbeat_leader_absent':
                // Jika tidak ada leader yang aktif, coba menjadi leader.
                tryToBecomeLeader();
                break;
            case 'activity':
                // Jika ada aktivitas dari tab lain, reset timer inaktivitas.
                resetInactivityTimer();
                break;
            case 'show_reconnect':
                // Tampilkan dialog reconnect jika koneksi terputus.
                showReconnectDialog();
                break;
            case 'hide_reconnect':
                // Sembunyikan dialog reconnect setelah berhasil reconnect.
                hideReconnectDialog();
                break;
            case 'tab_count_changed':
                // Update jumlah tab yang terbuka berdasarkan pesan yang diterima.
                const { tabCount: newTabCount, delta } = message;
                tabCount = newTabCount;
                localStorage.setItem('tab_count', tabCount);

                if (delta < 0) {
                    // Jika delta negatif, berarti ada tab yang ditutup.
                    console.log('Tab telah ditutup.');
                    // Cek apakah leader masih aktif. Jika tidak, coba menjadi leader.
                    setTimeout(function () {
                        if (!isLeaderAlive()) {
                            tryToBecomeLeader();
                            if (isHeartbeatLeader) {
                                // Heartbeat sudah dikirim di dalam becomeHeartbeatLeader
                                console.log('Heartbeat aktif dikirim setelah 1 detik penutupan tab.');
                            }
                        } else if (isHeartbeatLeader) {
                            // Jika sudah leader, langsung kirim active.
                            sendHeartbeat('active');
                            console.log('Leader mengirim heartbeat active setelah 1 detik penutupan tab.');
                        }
                    }, 1000);
                }
                // Jika delta positif, berarti ada tab yang dibuka. Tidak perlu melakukan apa-apa.
                break;
            default:
                break;
        }
    }

    /**
     * Fungsi untuk mendapatkan tabId dari current leader.
     * @returns {string|null} tabId dari leader atau null jika tidak ada.
     */
    function getCurrentLeaderId() {
        const currentLeader = localStorage.getItem('heartbeat_leader');
        if (!currentLeader) return null;
        const leaderData = JSON.parse(currentLeader);
        return leaderData.tabId;
    }

    /**
     * Fungsi untuk menampilkan pesan Reconnect menggunakan SweetAlert2.
     * Pesan ini muncul ketika koneksi terputus dan memberikan opsi kepada pengguna untuk reconnect atau logout.
     */
    function showReconnectDialog() {
        if (isDisconnected) return; // Jika sudah dalam status disconnected, tidak perlu menampilkan lagi.
        isDisconnected = true;

        // Kirim pesan ke tab lain bahwa reconnect dialog ditampilkan.
        broadcastMessage({ type: 'show_reconnect' });

        // Membungkus SweetAlert2 dalam setTimeout untuk memastikan prioritas lebih tinggi.
        setTimeout(() => {
            Swal.fire({
                title: 'Koneksi Terputus',
                text: 'Tidak dapat terhubung ke server. Akan logout otomatis dalam 10 detik.',
                icon: 'warning',
                timer: disconnectTimeoutDuration,
                timerProgressBar: true,
                showCancelButton: true,
                confirmButtonText: 'Reconnect',
                cancelButtonText: 'Logout Sekarang',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna memilih untuk reconnect, sembunyikan dialog dan kirim heartbeat aktif.
                    hideReconnectDialog();
                    sendHeartbeat('active'); // Coba kirim heartbeat lagi.
                } else {
                    // Jika pengguna memilih untuk logout, arahkan ke halaman logout dengan alasan 'disconnect'.
                    window.location.href = 'sidebarLogout?reason=disconnect';
                }
            });
        }, 0); // Delay 0 ms untuk memberikan prioritas lebih tinggi.

        // Atur timer untuk auto-logout setelah disconnectTimeout jika pengguna tidak merespon.
        disconnectTimer = setTimeout(function () {
            window.location.href = 'sidebarLogout?reason=disconnect';
        }, disconnectTimeoutDuration);
    }

    /**
     * Fungsi untuk menyembunyikan pesan Reconnect.
     * Mengatur flag isDisconnected menjadi false dan membersihkan timer reconnect.
     */
    function hideReconnectDialog() {
        isDisconnected = false;
        // Membungkus Swal.close dalam setTimeout untuk memastikan prioritas lebih tinggi.
        setTimeout(() => {
            Swal.close(); // Sembunyikan dialog SweetAlert2.
        }, 0);
        clearTimeout(disconnectTimer); // Hapus timer reconnect.

        // Kirim pesan ke tab lain bahwa reconnect dialog disembunyikan.
        broadcastMessage({ type: 'hide_reconnect' });
    }

    /**
     * Fungsi untuk mengirimkan Heartbeat.
     * Mengirim status sesi ('active', 'wait', atau 'inactive') ke server menggunakan Axios.
     * @param {string} status - Status sesi ('active', 'wait', atau 'inactive').
     */
    function sendHeartbeat(status = 'active') {
        axios.post('updateActivity', new URLSearchParams({ status: status }))
            .then(function (response) {
                console.log('Heartbeat dikirim:', response.data);
                if (isHeartbeatLeader && status === 'active') {
                    // Jika tab ini adalah leader dan mengirim status aktif, perbarui timestamp leader.
                    const leaderData = {
                        tabId: tabId,
                        timestamp: Date.now()
                    };
                    localStorage.setItem('heartbeat_leader', JSON.stringify(leaderData));
                    console.log('heartbeat_leader diperbarui:', leaderData);
                }
                if (isDisconnected) {
                    // Jika sebelumnya dalam status disconnected dan berhasil reconnect, sembunyikan dialog reconnect.
                    hideReconnectDialog();
                }
            })
            .catch(function (error) {
                console.error('Gagal mengirim Heartbeat:', error);
                if (!isUnloading) {
                    // Jika gagal mengirim heartbeat dan halaman tidak sedang di-unload, tampilkan dialog reconnect.
                    showReconnectDialog();
                }
            });
    }

    /**
     * Fungsi untuk menampilkan peringatan sebelum auto-logout akibat inaktivitas.
     * Menggunakan SweetAlert2 untuk menampilkan dialog peringatan.
     */
    function showWarning() {
        // Membungkus SweetAlert2 dalam setTimeout untuk memastikan prioritas lebih tinggi.
        setTimeout(() => {
            Swal.fire({
                title: 'Akan Logout Otomatis',
                text: 'Anda akan otomatis logout dalam 5 menit karena tidak ada aktivitas.',
                icon: 'info',
                timer: 300000, // 5 menit dalam milidetik.
                timerProgressBar: true,
                showCancelButton: true,
                confirmButtonText: 'Perpanjang Sesi',
                cancelButtonText: 'Logout Sekarang',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna memilih untuk memperpanjang sesi, reset timer inaktivitas dan tutup dialog.
                    resetInactivityTimer();
                    // Membungkus Swal.close dalam setTimeout untuk memastikan prioritas lebih tinggi.
                    setTimeout(() => {
                        Swal.close();
                    }, 0);
                } else {
                    // Jika pengguna memilih untuk logout, arahkan ke halaman logout dengan alasan 'inactivity'.
                    window.location.href = 'sidebarLogout?reason=inactivity';
                }
            });
        }, 0); // Delay 0 ms untuk memberikan prioritas lebih tinggi.
    }

    /**
     * Fungsi untuk mereset timer inaktivitas tanpa mengirim Heartbeat.
     * Mengatur ulang timer untuk peringatan dan auto-logout.
     */
    function resetInactivityTimer() {
        // Hapus timer sebelumnya.
        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);

        // Atur timer untuk menampilkan peringatan setelah warningTimeout.
        warningTimer = setTimeout(showWarning, warningTimeout);
        // Atur timer untuk auto-logout setelah inactivityTimeout.
        inactivityTimer = setTimeout(function () {
            window.location.href = 'sidebarLogout?reason=inactivity';
        }, inactivityTimeout);
    }

    /**
     * Fungsi untuk menangani penutupan halaman (unload events).
     * Mengatur status sesi menjadi 'wait' setiap kali tab ditutup atau di-refresh.
     */
    function handleUnloadEvents(event) {
        if (isUnloading) return; // Pastikan fungsi hanya dijalankan sekali.
        isUnloading = true;

        // Kurangi tabCount ketika tab ditutup, minimal 0.
        updateTabCount(-1);

        // Jika tab ini adalah leader, resign dari peran leader saat tab ditutup.
        if (isHeartbeatLeader) {
            resignLeadership();
        }

        // Sesuai instruksi, selalu kirim status 'wait' sebelum halaman ditutup atau di-refresh.
        const url = 'updateActivity';
        const data = new URLSearchParams({ status: 'wait' });
        const blob = new Blob([data], { type: 'application/x-www-form-urlencoded' });

        // Menggunakan sendBeacon untuk mengirim data sebelum unload.
        const beaconSent = navigator.sendBeacon(url, blob);
        if (beaconSent) {
            console.log('Mengirim heartbeat wait sebelum tab ditutup.');
        } else {
            console.warn('Gagal mengirim heartbeat wait menggunakan sendBeacon.');
        }

        // Tambahkan penundaan tambahan untuk memastikan beforeunload selesai sebelum halaman ditutup.
        // Ini dapat membantu memastikan bahwa SweetAlert2 dapat dijalankan dengan benar.
        setTimeout(() => {
            // Tidak melakukan apa-apa, hanya menunggu sebelum halaman benar-benar ditutup.
        }, 2000); // Tambah 2 detik
    }

    // Tambahkan event listener untuk berbagai event unload pada window.
    window.addEventListener('beforeunload', handleUnloadEvents);
    window.addEventListener('unload', handleUnloadEvents);
    window.addEventListener('pagehide', handleUnloadEvents);

    /**
     * Fungsi untuk mendaftarkan event listener untuk aktivitas pengguna.
     * Mengawasi berbagai jenis aktivitas seperti mouse movement, key press, click, scroll, dan touch.
     */
    function setupActivityListeners() {
        document.addEventListener('mousemove', handleActivity);
        document.addEventListener('keydown', handleActivity);
        document.addEventListener('click', handleActivity);
        document.addEventListener('scroll', handleActivity);
        document.addEventListener('touchstart', handleActivity);
    }

    /**
     * Fungsi untuk menangani aktivitas pengguna.
     * Reset timer inaktivitas dan kirim pesan aktivitas ke tab lain.
     * Tidak mengirim heartbeat di sini (sesuai instruksi).
     */
    function handleActivity() {
        resetInactivityTimer(); // Reset timer inaktivitas.
        broadcastMessage({ type: 'activity', timestamp: Date.now() }); // Kirim pesan aktivitas ke tab lain.
    }

    /**
     * Fungsi untuk mengelola Heartbeat antar tab.
     * Mengatur listener untuk pesan dan memeriksa keberadaan leader secara periodik.
     */
    function manageHeartbeatAcrossTabs() {
        // Setup listener untuk pesan dari tab lain.
        setupMessageListener();

        // Atur interval untuk memeriksa apakah leader masih aktif setiap 5 detik.
        setInterval(function () {
            if (!isLeaderAlive()) {
                console.log('Leader tidak aktif, mencoba menjadi leader.');
                tryToBecomeLeader();
            }
        }, 5000);
    }

    /**
     * Fungsi untuk mencoba menjadi Heartbeat Leader.
     * Hanya akan menjadi leader jika tab tidak sedang dalam status leader.
     */
    function tryToBecomeLeader() {
        if (!isHeartbeatLeader && !isUnloading) {
            // Jika belum menjadi leader dan tidak sedang unloading, coba menjadi leader.
            becomeHeartbeatLeader();
        }
    }

    /**
     * Fungsi untuk menjadi Heartbeat Leader.
     * Mengatur flag leader, mengirim pesan ke tab lain, dan mulai mengirim heartbeat aktif secara berkala.
     */
    function becomeHeartbeatLeader() {
        // Cek kembali apakah leader sudah ada sebelum mencoba menjadi leader.
        const currentLeader = localStorage.getItem('heartbeat_leader');
        if (currentLeader) {
            const leaderData = JSON.parse(currentLeader);
            const now = Date.now();
            // Jika leader masih aktif, tidak perlu menjadi leader.
            if (now - leaderData.timestamp < heartbeatInterval * 2) { // Dua kali interval heartbeat
                return;
            }
        }

        // Atur diri sebagai leader.
        const newLeaderData = {
            tabId: tabId,
            timestamp: Date.now()
        };
        localStorage.setItem('heartbeat_leader', JSON.stringify(newLeaderData));

        // Tunggu sebentar sebelum memverifikasi leadership
        setTimeout(() => {
            if (isUnloading) return; // Jangan lanjutkan jika sedang unloading.

            const latestLeader = localStorage.getItem('heartbeat_leader');
            if (latestLeader) {
                const parsedLeader = JSON.parse(latestLeader);
                if (parsedLeader.tabId === tabId) {
                    // Confirmed as leader
                    isHeartbeatLeader = true;

                    // Kirim pesan bahwa leader telah hadir, termasuk tabId
                    broadcastMessage({ type: 'heartbeat_leader_present', tabId: tabId });

                    // Kirim heartbeat 'active' segera setelah menjadi leader.
                    sendHeartbeat('active');

                    // Atur interval untuk mengirim heartbeat 'active' setiap 30 detik.
                    heartbeatTimer = setInterval(function () {
                        if (!isUnloading) {
                            sendHeartbeat('active');
                        }
                    }, heartbeatInterval);

                    console.log('Tab ini menjadi leader dan mengirim heartbeat active.');
                } else {
                    // Leader sudah ditetapkan oleh tab lain
                    console.log('Tab ini gagal menjadi leader karena sudah ada leader lain.');
                }
            }
        }, 100); // Tunggu 100ms
    }

    /**
     * Fungsi untuk resign sebagai Heartbeat Leader.
     * Menghapus flag leader, menghentikan timer heartbeat, dan mengirim pesan bahwa leader telah absen.
     */
    function resignLeadership() {
        if (!isHeartbeatLeader) return; // Jika bukan leader, tidak perlu melakukan apapun.

        // Pastikan hanya leader yang bisa resign.
        const currentLeader = localStorage.getItem('heartbeat_leader');
        if (currentLeader) {
            const leaderData = JSON.parse(currentLeader);
            if (leaderData.tabId !== tabId) {
                // Jika tab yang mencoba resign bukan leader, abaikan.
                return;
            }
        }

        isHeartbeatLeader = false; // Tandai bahwa tab ini tidak lagi sebagai leader.
        localStorage.removeItem('heartbeat_leader'); // Hapus timestamp leader dari localStorage.

        // Kirim pesan bahwa leader telah absen.
        broadcastMessage({ type: 'heartbeat_leader_absent' });

        // Hentikan timer heartbeat jika ada.
        if (heartbeatTimer) {
            clearInterval(heartbeatTimer);
            heartbeatTimer = null;
        }

        console.log('Tab ini melepaskan peran leader.');
    }

    /**
     * Fungsi untuk memeriksa apakah leader masih aktif.
     * Leader dianggap aktif jika heartbeat terakhir kurang dari 35 detik yang lalu.
     * @returns {boolean} True jika leader masih aktif, False jika tidak.
     */
    function isLeaderAlive() {
        const currentLeader = localStorage.getItem('heartbeat_leader');
        if (!currentLeader) return false; // Tidak ada leader yang aktif.

        const leaderData = JSON.parse(currentLeader);
        const now = Date.now();
        return (now - leaderData.timestamp) < 35000; // Leader aktif jika heartbeat terakhir < 35 detik yang lalu.
    }

    /**
     * Fungsi untuk memeriksa apakah tab saat ini aktif (visible).
     * Menggunakan Page Visibility API.
     * @returns {boolean} True jika tab aktif, False jika tidak.
     */
    function isTabActive() {
        return document.visibilityState === 'visible';
    }

    /**
     * Fungsi untuk menangani perubahan visibilitas tab.
     * Leader tidak melepaskan perannya ketika tab menjadi tidak aktif.
     */
    function handleVisibilityChange() {
        if (document.visibilityState === 'visible') {
            console.log('Tab menjadi aktif.');
            // Jika leader tidak aktif, coba jadi leader.
            if (!isLeaderAlive()) {
                tryToBecomeLeader();
            }
        } else {
            console.log('Tab menjadi tidak aktif.');
            // Jangan resign leader di sini agar leader tetap mengirim heartbeat walaupun tab hidden.
        }
    }

    // Tambahkan event listener untuk perubahan visibilitas tab.
    document.addEventListener('visibilitychange', handleVisibilityChange);

    /**
     * Event listener untuk perubahan storage.
     * Jika terjadi perubahan pada key 'heartbeat_leader', periksa apakah leader masih aktif.
     */
    window.addEventListener('storage', function (event) {
        if (event.key === 'heartbeat_leader') {
            if (!isLeaderAlive()) {
                localStorage.removeItem('heartbeat_leader');
                broadcastMessage({ type: 'heartbeat_leader_absent' });
                console.log('Leader telah dihapus karena tidak aktif.');
            }
        }
    });

    /**
     * Pada saat halaman dimuat (DOMContentLoaded), kita tidak langsung mengirim heartbeat.
     * Menunggu 1 detik sebelum mengirim heartbeat pertama.
     * Heartbeat pertama setelah 1 detik adalah 'active'.
     */
    document.addEventListener('DOMContentLoaded', function () {
        // Reset timer inaktivitas dan setup activity listener.
        resetInactivityTimer();
        setupActivityListeners();
        manageHeartbeatAcrossTabs();

        // Setelah 1 detik halaman dibuka, kirim heartbeat active jika kita leader atau coba jadi leader.
        setTimeout(function () {
            if (!isLeaderAlive()) {
                tryToBecomeLeader();
                if (isHeartbeatLeader) {
                    // 'sendHeartbeat' telah dipanggil di 'becomeHeartbeatLeader'.
                    console.log('Heartbeat pertama (active) dikirim setelah 1 detik halaman dibuka.');
                }
            } else {
                // Jika sudah ada leader yang aktif, biarkan leader mengirim periodic heartbeat.
                if (isHeartbeatLeader) {
                    // Membungkus SweetAlert2 dalam setTimeout untuk memastikan prioritas lebih tinggi.
                    setTimeout(() => {
                        sendHeartbeat('active');
                        console.log('Tab ini adalah leader dan mengirim active heartbeat setelah 1 detik.');
                    }, 0);
                }
            }
        }, 1000); // Delay 1 detik.
    });

})();