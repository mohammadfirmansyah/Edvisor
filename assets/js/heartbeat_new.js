/**
 * heartbeat_new.js
 *
 * Mengelola pengiriman heartbeat secara periodik dan deteksi inaktivitas pengguna.
 * Menggunakan Axios untuk permintaan HTTP, BroadcastChannel atau fallback localStorage untuk sinkronisasi antar tab,
 * serta strategi event visibilitychange dan sendBeacon untuk mengirim status 'wait' pada saat tab ditutup atau di-refresh
 * tanpa mengganggu flashdata.
 */

(function () {
    // Pastikan Axios tersedia
    if (typeof axios === 'undefined') {
        console.error('Axios tidak ditemukan. Pastikan untuk menyertakan Axios sebelum heartbeat_new.js.');
        return;
    }

    // Pastikan SweetAlert2 (Swal) tersedia
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 tidak ditemukan. Pastikan untuk menyertakan SweetAlert2 sebelum heartbeat_new.js.');
        return;
    }

    // Cek apakah BroadcastChannel didukung oleh browser
    const isBroadcastChannelSupported = typeof BroadcastChannel !== 'undefined';
    // Inisialisasi channel jika BroadcastChannel didukung
    const channel = isBroadcastChannelSupported ? new BroadcastChannel('heartbeat_channel') : null;

    let isHeartbeatLeader = false; // Menandakan apakah tab ini adalah leader dalam pengiriman heartbeat
    let heartbeatTimer = null; // Timer untuk pengiriman heartbeat berkala
    const heartbeatInterval = 30000; // Interval heartbeat dalam milidetik (30 detik)
    let loginTimestamp = parseInt(localStorage.getItem('login_timestamp'), 10);
    if (!loginTimestamp) {
        loginTimestamp = Date.now();
        localStorage.setItem('login_timestamp', loginTimestamp);
    }

    // Membuat ID unik untuk setiap tab
    const tabId = `${Date.now()}-${Math.floor(Math.random() * 1000000)}`;
    localStorage.setItem('current_tab_id', tabId);

    // Timeout inaktivitas: 25 menit untuk peringatan, 30 menit untuk logout otomatis
    const warningTimeout = 25 * 60 * 1000; 
    const inactivityTimeout = 30 * 60 * 1000;
    let inactivityTimer;
    let warningTimer;

    let isDisconnected = false; // Menandakan apakah koneksi terputus
    let disconnectTimer;
    const disconnectTimeoutDuration = 10000; // Durasi timeout disconnect dalam milidetik (10 detik)

    let isUnloading = false; // Menandakan apakah halaman sedang dalam proses unload
    let isClosing = false; // Menandai apakah tab sedang akan ditutup atau direfresh

    let tabCount = parseInt(localStorage.getItem('tab_count'), 10) || 0;

    /**
     * Memperbarui jumlah tab yang terbuka.
     * @param {number} delta - Perubahan jumlah tab (positif untuk menambah, negatif untuk mengurangi).
     */
    function updateTabCount(delta) {
        tabCount += delta;
        tabCount = Math.max(tabCount, 0); // Pastikan tabCount tidak negatif
        localStorage.setItem('tab_count', tabCount);
        broadcastMessage({ type: 'tab_count_changed', tabCount: tabCount, delta: delta });
    }

    // Tambah tabCount saat tab dibuka
    updateTabCount(1);

    /**
     * Mengirim pesan ke semua tab lain melalui BroadcastChannel atau localStorage.
     * @param {Object} message - Objek pesan yang akan dikirim.
     */
    function broadcastMessage(message) {
        if (isBroadcastChannelSupported && channel) {
            channel.postMessage(message);
        } else {
            // Fallback menggunakan localStorage
            localStorage.setItem('heartbeat_message', JSON.stringify({ ...message, timestamp: Date.now() }));
            setTimeout(() => {
                localStorage.removeItem('heartbeat_message');
            }, 0);
        }
    }

    /**
     * Mengatur listener untuk menerima pesan dari BroadcastChannel atau localStorage.
     */
    function setupMessageListener() {
        if (isBroadcastChannelSupported && channel) {
            channel.onmessage = function (event) {
                handleMessage(event.data);
            };
        } else {
            window.addEventListener('storage', function (event) {
                if (event.key === 'heartbeat_message' && event.newValue) {
                    const message = JSON.parse(event.newValue);
                    handleMessage(message);
                }
            });
        }
    }

    /**
     * Menangani pesan yang diterima dari BroadcastChannel atau localStorage.
     * @param {Object} message - Objek pesan yang diterima.
     */
    function handleMessage(message) {
        if (!message || typeof message !== 'object') return;

        switch (message.type) {
            case 'heartbeat_leader_present':
                if (isHeartbeatLeader && message.tabId !== tabId) {
                    resignLeadership();
                }
                break;
            case 'heartbeat_leader_absent':
                tryToBecomeLeader();
                break;
            case 'activity':
                resetInactivityTimer();
                break;
            case 'show_reconnect':
                showReconnectDialog();
                break;
            case 'hide_reconnect':
                hideReconnectDialog();
                break;
            case 'tab_count_changed':
                const { tabCount: newTabCount, delta } = message;
                tabCount = newTabCount;
                localStorage.setItem('tab_count', tabCount);

                if (delta < 0) {
                    // Jika tab ditutup, tunggu 1 detik lalu cek apakah perlu kirim active
                    setTimeout(function () {
                        if (!isLeaderAlive()) {
                            tryToBecomeLeader();
                            if (isHeartbeatLeader) {
                                console.log('Heartbeat aktif dikirim setelah 1 detik penutupan tab.');
                            }
                        } else if (isHeartbeatLeader) {
                            sendHeartbeat('active');
                            console.log('Leader mengirim heartbeat active setelah 1 detik penutupan tab.');
                        }
                    }, 1000);
                }
                break;
            default:
                break;
        }
    }

    /**
     * Mendapatkan ID leader saat ini dari localStorage.
     * @returns {string|null} - ID leader atau null jika tidak ada leader.
     */
    function getCurrentLeaderId() {
        const currentLeader = localStorage.getItem('heartbeat_leader');
        if (!currentLeader) return null;
        const leaderData = JSON.parse(currentLeader);
        return leaderData.tabId;
    }

    /**
     * Menampilkan dialog reconnect menggunakan SweetAlert2.
     * Jika pengguna tidak mereconnect dalam durasi tertentu, akan logout otomatis.
     */
    function showReconnectDialog() {
        if (isDisconnected) return;
        isDisconnected = true;
        broadcastMessage({ type: 'show_reconnect' });

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
                    hideReconnectDialog();
                    sendHeartbeat('active');
                } else {
                    window.location.href = 'sidebarLogout?reason=disconnect';
                }
            });
        }, 0);

        disconnectTimer = setTimeout(function () {
            window.location.href = 'sidebarLogout?reason=disconnect';
        }, disconnectTimeoutDuration);
    }

    /**
     * Menyembunyikan dialog reconnect.
     */
    function hideReconnectDialog() {
        isDisconnected = false;
        setTimeout(() => {
            Swal.close();
        }, 0);
        clearTimeout(disconnectTimer);
        broadcastMessage({ type: 'hide_reconnect' });
    }

    /**
     * Mengirim heartbeat ke server dengan status tertentu.
     * @param {string} [status='active'] - Status heartbeat yang dikirim ('active' atau 'wait').
     */
    function sendHeartbeat(status = 'active') {
        axios.post('updateActivity', new URLSearchParams({ status: status }))
            .then(function (response) {
                console.log('Heartbeat dikirim:', response.data);
                if (isHeartbeatLeader && status === 'active') {
                    const leaderData = {
                        tabId: tabId,
                        timestamp: Date.now()
                    };
                    localStorage.setItem('heartbeat_leader', JSON.stringify(leaderData));
                }
                if (isDisconnected) {
                    hideReconnectDialog();
                }
            })
            .catch(function (error) {
                console.error('Gagal mengirim Heartbeat:', error);
                if (!isUnloading) {
                    showReconnectDialog();
                }
            });
    }

    /**
     * Menampilkan peringatan kepada pengguna bahwa mereka akan logout otomatis karena inaktivitas.
     */
    function showWarning() {
        setTimeout(() => {
            Swal.fire({
                title: 'Akan Logout Otomatis',
                text: 'Anda akan otomatis logout dalam 5 menit karena tidak ada aktivitas.',
                icon: 'info',
                timer: 300000, // 5 menit
                timerProgressBar: true,
                showCancelButton: true,
                confirmButtonText: 'Perpanjang Sesi',
                cancelButtonText: 'Logout Sekarang',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    resetInactivityTimer();
                    setTimeout(() => {
                        Swal.close();
                    }, 0);
                } else {
                    window.location.href = 'sidebarLogout?reason=inactivity';
                }
            });
        }, 0);
    }

    /**
     * Mereset timer inaktivitas pengguna.
     * Jika tidak ada aktivitas dalam waktu yang ditentukan, akan menampilkan peringatan atau logout otomatis.
     */
    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);
        warningTimer = setTimeout(showWarning, warningTimeout);
        inactivityTimer = setTimeout(function () {
            window.location.href = 'sidebarLogout?reason=inactivity';
        }, inactivityTimeout);
    }

    /**
     * Perubahan penting di sini:
     * Kita tidak langsung mengirim status 'wait' pada beforeunload.
     * Sebagai gantinya, kita hanya menandai bahwa tab akan ditutup (isClosing = true).
     * Lalu pada visibilitychange, ketika halaman menjadi hidden, kita cek isClosing.
     * Jika benar, kita gunakan sendBeacon untuk mengirim 'wait'.
     */

    /**
     * Menangani event sebelum halaman di-unload (seperti saat tab ditutup atau direfresh).
     * Menandai bahwa tab akan ditutup dan memperbarui jumlah tab.
     * @param {Event} event - Event beforeunload.
     */
    function handleBeforeUnloadEvents(event) {
        if (isUnloading) return;
        isUnloading = true;
        isClosing = true; // Menandai bahwa tab sedang akan ditutup
        updateTabCount(-1);

        // Jika tab ini adalah leader, resign leadership
        if (isHeartbeatLeader) {
            resignLeadership();
        }

        // Tidak mengirim apa-apa di sini. Hanya menandai isClosing = true.
        // Pengiriman status 'wait' akan dilakukan nanti pada visibilitychange atau unload.
    }

    /**
     * Menangani event unload sebagai fallback jika visibilitychange tidak sempat diproses.
     * @param {Event} event - Event unload.
     */
    function handleUnloadEvent(event) {
        if (isClosing) {
            sendWaitBeforeClose();
        }
    }

    /**
     * Mengirim status 'wait' menggunakan sendBeacon sebelum halaman benar-benar hilang.
     */
    function sendWaitBeforeClose() {
        const url = 'updateActivity';
        const data = new URLSearchParams({ status: 'wait' });
        const blob = new Blob([data], { type: 'application/x-www-form-urlencoded' });
        const beaconSent = navigator.sendBeacon(url, blob);
        if (beaconSent) {
            console.log('Mengirim heartbeat wait sebelum tab ditutup (sendBeacon).');
        } else {
            console.warn('Gagal mengirim heartbeat wait menggunakan sendBeacon.');
        }
    }

    // Menambahkan event listener untuk beforeunload dan unload
    window.addEventListener('beforeunload', handleBeforeUnloadEvents);
    window.addEventListener('unload', handleUnloadEvent);

    /**
     * Menangani perubahan visibilitas dokumen.
     * Jika dokumen menjadi hidden dan isClosing=true, mengirim status 'wait'.
     * Jika dokumen kembali visible namun isClosing=true, reset isClosing.
     */
    document.addEventListener('visibilitychange', function () {
        // Jika halaman jadi hidden dan sebelumnya isClosing = true, berarti user menutup tab atau refresh
        if (document.visibilityState === 'hidden' && isClosing) {
            sendWaitBeforeClose();
        }

        // Jika halaman kembali visible namun isClosing sempat true, mungkin user membatalkan penutupan
        if (document.visibilityState === 'visible' && isClosing) {
            // User mungkin membatalkan penutupan tab, reset kondisi
            isClosing = false;
        }
    });

    /**
     * Mengatur listener untuk aktivitas pengguna seperti mouse, keyboard, klik, scroll, dan sentuhan.
     */
    function setupActivityListeners() {
        document.addEventListener('mousemove', handleActivity);
        document.addEventListener('keydown', handleActivity);
        document.addEventListener('click', handleActivity);
        document.addEventListener('scroll', handleActivity);
        document.addEventListener('touchstart', handleActivity);
    }

    /**
     * Menangani aktivitas pengguna dengan mereset timer inaktivitas dan memberitahu tab lain.
     */
    function handleActivity() {
        resetInactivityTimer();
        broadcastMessage({ type: 'activity', timestamp: Date.now() });
    }

    /**
     * Mengelola pengiriman heartbeat antar tab yang terbuka.
     */
    function manageHeartbeatAcrossTabs() {
        setupMessageListener();
        // Cek setiap 5 detik apakah leader masih aktif
        setInterval(function () {
            if (!isLeaderAlive()) {
                tryToBecomeLeader();
            }
        }, 5000);
    }

    /**
     * Mencoba untuk menjadi leader jika belum ada leader yang aktif.
     */
    function tryToBecomeLeader() {
        if (!isHeartbeatLeader && !isUnloading) {
            becomeHeartbeatLeader();
        }
    }

    /**
     * Menetapkan tab ini sebagai leader untuk pengiriman heartbeat.
     */
    function becomeHeartbeatLeader() {
        const currentLeader = localStorage.getItem('heartbeat_leader');
        if (currentLeader) {
            const leaderData = JSON.parse(currentLeader);
            const now = Date.now();
            // Cek apakah leader masih aktif berdasarkan timestamp
            if (now - leaderData.timestamp < heartbeatInterval * 2) {
                return; // Leader masih aktif, tidak perlu menjadi leader
            }
        }

        const newLeaderData = { tabId: tabId, timestamp: Date.now() };
        localStorage.setItem('heartbeat_leader', JSON.stringify(newLeaderData));

        setTimeout(() => {
            if (isUnloading) return;
            const latestLeader = localStorage.getItem('heartbeat_leader');
            if (latestLeader) {
                const parsedLeader = JSON.parse(latestLeader);
                if (parsedLeader.tabId === tabId) {
                    isHeartbeatLeader = true;
                    broadcastMessage({ type: 'heartbeat_leader_present', tabId: tabId });
                    sendHeartbeat('active');
                    // Atur interval untuk mengirim heartbeat secara berkala
                    heartbeatTimer = setInterval(function () {
                        if (!isUnloading) {
                            sendHeartbeat('active');
                        }
                    }, heartbeatInterval);
                    console.log('Tab ini menjadi leader dan mengirim heartbeat active.');
                }
            }
        }, 100);
    }

    /**
     * Melepaskan peran sebagai leader dan memberitahu tab lain.
     */
    function resignLeadership() {
        if (!isHeartbeatLeader) return;
        const currentLeader = localStorage.getItem('heartbeat_leader');
        if (currentLeader) {
            const leaderData = JSON.parse(currentLeader);
            if (leaderData.tabId !== tabId) {
                return; // Leader sudah digantikan oleh tab lain
            }
        }

        isHeartbeatLeader = false;
        localStorage.removeItem('heartbeat_leader');
        broadcastMessage({ type: 'heartbeat_leader_absent' });
        if (heartbeatTimer) {
            clearInterval(heartbeatTimer);
            heartbeatTimer = null;
        }
        console.log('Tab ini melepaskan peran leader.');
    }

    /**
     * Memeriksa apakah leader masih aktif berdasarkan timestamp terakhir heartbeat.
     * @returns {boolean} - True jika leader masih aktif, false jika tidak.
     */
    function isLeaderAlive() {
        const currentLeader = localStorage.getItem('heartbeat_leader');
        if (!currentLeader) return false;
        const leaderData = JSON.parse(currentLeader);
        const now = Date.now();
        // Leader dianggap aktif jika heartbeat terakhir kurang dari 35 detik
        return (now - leaderData.timestamp) < 35000;
    }

    /**
     * Memeriksa apakah tab saat ini aktif (terlihat oleh pengguna).
     * @returns {boolean} - True jika tab aktif, false jika tidak.
     */
    function isTabActive() {
        return document.visibilityState === 'visible';
    }

    /**
     * Menangani perubahan visibilitas dokumen untuk mencoba menjadi leader jika diperlukan.
     */
    function handleVisibilityChange() {
        if (document.visibilityState === 'visible') {
            if (!isLeaderAlive()) {
                tryToBecomeLeader();
            }
        }
    }

    // Menambahkan listener untuk perubahan visibilitas dokumen
    document.addEventListener('visibilitychange', handleVisibilityChange);

    /**
     * Menangani perubahan pada localStorage khusus untuk heartbeat_leader.
     * Jika leader tidak aktif, hapus data leader dan beri tahu tab lain.
     */
    window.addEventListener('storage', function (event) {
        if (event.key === 'heartbeat_leader') {
            if (!isLeaderAlive()) {
                localStorage.removeItem('heartbeat_leader');
                broadcastMessage({ type: 'heartbeat_leader_absent' });
            }
        }
    });

    /**
     * Inisialisasi saat dokumen telah dimuat.
     * Mengatur timer inaktivitas, listener aktivitas, dan manajemen heartbeat antar tab.
     * Juga mengirim heartbeat pertama setelah 1 detik halaman dibuka.
     */
    document.addEventListener('DOMContentLoaded', function () {
        resetInactivityTimer();
        setupActivityListeners();
        manageHeartbeatAcrossTabs();

        // Heartbeat pertama setelah 1 detik halaman dibuka
        setTimeout(function () {
            if (!isLeaderAlive()) {
                tryToBecomeLeader();
                if (isHeartbeatLeader) {
                    console.log('Heartbeat pertama (active) dikirim setelah 1 detik halaman dibuka.');
                }
            } else {
                if (isHeartbeatLeader) {
                    setTimeout(() => {
                        sendHeartbeat('active');
                        console.log('Tab ini adalah leader dan mengirim active heartbeat setelah 1 detik.');
                    }, 0);
                }
            }
        }, 1000);
    });
})();