/**
 * heartbeat.js
 *
 * Mengelola pengiriman heartbeat secara periodik dan deteksi inaktivitas pengguna.
 * Menggunakan Axios untuk permintaan HTTP dan BroadcastChannel atau alternatif untuk sinkronisasi antar tab.
 * Menggunakan Page Visibility API dan Shared Timing untuk memastikan hanya satu heartbeat dikirim setiap 30 detik.
 */

(function () {
    // Cek apakah Axios tersedia
    if (typeof axios === 'undefined') {
        console.error('Axios tidak ditemukan. Pastikan untuk menyertakan Axios sebelum heartbeat.js.');
        return;
    }

    // Cek apakah BroadcastChannel tersedia
    const isBroadcastChannelSupported = typeof BroadcastChannel !== 'undefined';
    if (!isBroadcastChannelSupported) {
        console.warn('BroadcastChannel tidak didukung oleh browser ini. Menggunakan fallback.');
    }

    // Inisialisasi BroadcastChannel atau fallback menggunakan Event Listener pada localStorage
    const channel = isBroadcastChannelSupported ? new BroadcastChannel('heartbeat_channel') : null;

    // Flag untuk menentukan apakah tab ini adalah leader yang mengirim heartbeat
    let isHeartbeatLeader = false;

    // Variabel untuk menyimpan timer heartbeat
    let heartbeatTimer = null;
    let nextHeartbeatTimeout = null;

    // Waktu interval heartbeat (30.000 milidetik = 30 detik)
    const heartbeatInterval = 30000;

    // Waktu login pengguna (timestamp)
    let loginTimestamp = parseInt(sessionStorage.getItem('login_timestamp'), 10);

    // Jika tidak ada loginTimestamp di sessionStorage, inisialisasi dengan waktu sekarang
    if (!loginTimestamp) {
        loginTimestamp = Date.now();
        sessionStorage.setItem('login_timestamp', loginTimestamp);
    }

    /**
     * Fungsi untuk menghitung waktu hingga heartbeat berikutnya
     * @returns {number} Waktu dalam milidetik hingga heartbeat berikutnya
     */
    function timeUntilNextHeartbeat() {
        const now = Date.now();
        return heartbeatInterval - ((now - loginTimestamp) % heartbeatInterval);
    }

    // Batas waktu inaktivitas sebelum peringatan (25 menit = 1.500.000 milidetik)
    const warningTimeout = 1500000;

    // Batas waktu inaktivitas sebelum auto-logout (30 menit = 1.800.000 milidetik)
    const inactivityTimeout = 1800000;

    // Timer untuk inaktivitas dan peringatan
    let inactivityTimer;
    let warningTimer;

    // Variabel untuk mengelola reconnect
    let isDisconnected = false;
    let disconnectTimer;
    const disconnectTimeout = 10000; // 10 detik

    // Flag untuk menandai apakah halaman sedang di-unload
    let isUnloading = false;

    // Counter untuk jumlah tab yang terbuka
    let tabCount = parseInt(sessionStorage.getItem('tab_count'), 10) || 0;

    // Tambahkan tabCount ketika tab dibuka
    tabCount++;
    sessionStorage.setItem('tab_count', tabCount);

    // Mengirim pesan ke tab lain bahwa tabCount telah berubah
    broadcastMessage({ type: 'tab_count_changed', tabCount: tabCount });

    /**
     * Fungsi untuk mengirim pesan melalui BroadcastChannel atau fallback
     * @param {Object} message - Pesan yang akan dikirim
     */
    function broadcastMessage(message) {
        if (isBroadcastChannelSupported && channel) {
            channel.postMessage(message);
        } else {
            // Fallback menggunakan localStorage
            localStorage.setItem('heartbeat_message', JSON.stringify({ ...message, timestamp: Date.now() }));
            // Hapus pesan untuk menghindari akumulasi
            setTimeout(() => {
                localStorage.removeItem('heartbeat_message');
            }, 0);
        }
    }

    /**
     * Fungsi untuk menangani pesan yang diterima melalui BroadcastChannel atau fallback
     */
    function setupMessageListener() {
        if (isBroadcastChannelSupported && channel) {
            channel.onmessage = function (event) {
                handleMessage(event.data);
            };
        } else {
            // Fallback menggunakan event storage
            window.addEventListener('storage', function (event) {
                if (event.key === 'heartbeat_message' && event.newValue) {
                    const message = JSON.parse(event.newValue);
                    handleMessage(message);
                }
            });
        }
    }

    /**
     * Fungsi untuk menangani pesan yang diterima
     * @param {Object} message - Pesan yang diterima
     */
    function handleMessage(message) {
        if (!message || typeof message !== 'object') return;

        switch (message.type) {
            case 'heartbeat_leader_present':
                // Leader sudah ada, tab ini bukan leader
                if (isHeartbeatLeader) {
                    resignLeadership();
                }
                break;
            case 'heartbeat_leader_absent':
                // Leader tidak ada, coba menjadi leader
                tryToBecomeLeader();
                break;
            case 'activity':
                // Terima aktivitas dari tab lain, reset timer
                resetInactivityTimer();
                break;
            case 'show_reconnect':
                // Tampilkan pesan reconnect
                showReconnectDialog();
                break;
            case 'hide_reconnect':
                // Sembunyikan pesan reconnect
                hideReconnectDialog();
                break;
            case 'tab_count_changed':
                // Update tabCount
                tabCount = message.tabCount;
                sessionStorage.setItem('tab_count', tabCount);
                break;
            default:
                break;
        }
    }

    /**
     * Fungsi untuk menampilkan pesan Reconnect dengan SweetAlert2
     */
    function showReconnectDialog() {
        if (isDisconnected) return; // Jika sudah terputus, tidak perlu menampilkan lagi
        isDisconnected = true;

        // Mengirim pesan ke tab lain untuk menampilkan reconnect dialog
        broadcastMessage({ type: 'show_reconnect' });

        Swal.fire({
            title: 'Koneksi Terputus',
            text: 'Tidak dapat terhubung ke server. Akan logout otomatis dalam 10 detik.',
            icon: 'warning',
            timer: disconnectTimeout,
            timerProgressBar: true,
            showCancelButton: true,
            confirmButtonText: 'Reconnect',
            cancelButtonText: 'Logout Sekarang',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Pengguna menekan tombol Reconnect
                hideReconnectDialog();
                sendHeartbeat('active'); // Coba kirim heartbeat lagi
            } else {
                // Redirect ke sidebarLogout dengan alasan 'disconnect'
                window.location.href = 'sidebarLogout?reason=disconnect';
            }
        });

        // Timer untuk logout otomatis setelah 10 detik
        disconnectTimer = setTimeout(function () {
            window.location.href = 'sidebarLogout?reason=disconnect';
        }, disconnectTimeout);
    }

    /**
     * Fungsi untuk menyembunyikan pesan Reconnect
     */
    function hideReconnectDialog() {
        isDisconnected = false;
        Swal.close();
        clearTimeout(disconnectTimer);

        // Mengirim pesan ke tab lain untuk menyembunyikan reconnect dialog
        broadcastMessage({ type: 'hide_reconnect' });
    }

    /**
     * Fungsi untuk mengirimkan Heartbeat.
     * @param {string} status - Status sesi, 'active' atau 'inactive'.
     */
    function sendHeartbeat(status = 'active') {
        axios.post('updateActivity', new URLSearchParams({ status: status }))
            .then(function (response) {
                // console.log('Heartbeat dikirim:', response.data);
                if (isHeartbeatLeader && status === 'active') {
                    // Perbarui timestamp leader hanya jika heartbeat aktif berhasil dikirim
                    sessionStorage.setItem('heartbeat_leader', Date.now());
                    // console.log('heartbeat_leader diperbarui:', Date.now());
                }
                if (isDisconnected) {
                    hideReconnectDialog();
                }
            })
            .catch(function (error) {
                console.error('Gagal mengirim Heartbeat:', error);
                // Tampilkan pesan Reconnect hanya jika tidak sedang unloading
                if (!isUnloading) {
                    showReconnectDialog();
                }
            });
    }

    /**
     * Menampilkan peringatan sebelum auto-logout
     */
    function showWarning() {
        Swal.fire({
            title: 'Akan Logout Otomatis',
            text: 'Anda akan otomatis logout dalam 5 menit karena tidak ada aktivitas.',
            icon: 'info',
            timer: 300000, // 5 menit = 300.000 milidetik
            timerProgressBar: true,
            showCancelButton: true,
            confirmButtonText: 'Perpanjang Sesi',
            cancelButtonText: 'Logout Sekarang',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Perpanjang sesi tanpa mengirim Heartbeat tambahan
                resetInactivityTimer();
                Swal.close(); // Tutup modal setelah perpanjangan sesi
            } else {
                // Redirect ke sidebarLogout dengan alasan 'inactivity'
                window.location.href = 'sidebarLogout?reason=inactivity';
            }
        });
    }

    /**
     * Reset timer inaktivitas tanpa mengirim Heartbeat
     */
    function resetInactivityTimer() {
        // Reset timer inaktivitas
        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);

        // Atur timer peringatan 5 menit sebelum auto-logout
        warningTimer = setTimeout(showWarning, warningTimeout);

        // Atur timer auto-logout setelah 30 menit
        inactivityTimer = setTimeout(function () {
            // Redirect ke sidebarLogout dengan alasan 'inactivity'
            window.location.href = 'sidebarLogout?reason=inactivity';
        }, inactivityTimeout);
    }

    /**
     * Menangani penutupan halaman
     */
    function handleUnloadEvents() {
        // Set flag bahwa halaman sedang di-unload
        isUnloading = true;

        // Kurangi tabCount ketika tab ditutup
        tabCount = parseInt(sessionStorage.getItem('tab_count'), 10) || 1;
        tabCount = Math.max(tabCount - 1, 0);
        sessionStorage.setItem('tab_count', tabCount);

        // Mengirim pesan ke tab lain bahwa tabCount telah berubah
        broadcastMessage({ type: 'tab_count_changed', tabCount: tabCount });

        if (isHeartbeatLeader) {
            resignLeadership();
        }

        // Jika tabCount mencapai 0, kirim heartbeat dengan status 'inactive' menggunakan sendBeacon
        if (tabCount === 0) {
            const url = 'updateActivity';
            const data = new URLSearchParams({ status: 'inactive' });
            const blob = new Blob([data], { type: 'application/x-www-form-urlencoded' });
            const beaconSent = navigator.sendBeacon(url, blob);

            if (beaconSent) {
                // console.log('Semua tab ditutup, mengirim heartbeat inactive via sendBeacon.');
            } else {
                console.warn('Gagal mengirim heartbeat inactive menggunakan sendBeacon.');
            }
        }
    }

    // Menambahkan semua event listener untuk unload
    window.addEventListener('beforeunload', handleUnloadEvents);
    window.addEventListener('unload', handleUnloadEvents);
    window.addEventListener('pagehide', handleUnloadEvents);

    /**
     * Mendaftarkan event listener untuk aktivitas pengguna
     */
    function setupActivityListeners() {
        document.addEventListener('mousemove', handleActivity);
        document.addEventListener('keydown', handleActivity);
        document.addEventListener('click', handleActivity);
        document.addEventListener('scroll', handleActivity);
        // Tambahkan event lain jika diperlukan
        document.addEventListener('touchstart', handleActivity); // Untuk perangkat mobile
    }

    /**
     * Menangani aktivitas pengguna dan reset timer
     */
    function handleActivity() {
        resetInactivityTimer();
        // Mengirim pesan aktivitas ke tab lain
        broadcastMessage({ type: 'activity', timestamp: Date.now() });
    }

    /**
     * Mengelola Heartbeat antar tab untuk mencegah pengiriman Heartbeat ganda
     */
    function manageHeartbeatAcrossTabs() {
        // Jika belum ada leader, coba menjadi leader
        if (!sessionStorage.getItem('heartbeat_leader')) {
            // Tambahkan delay 1 detik sebelum mencoba menjadi leader
            setTimeout(tryToBecomeLeader, 1000);
        }

        // Memasang listener untuk pesan
        setupMessageListener();

        // Memeriksa keberadaan leader setiap 5 detik
        setInterval(function () {
            if (!isLeaderAlive()) {
                // console.log('Leader tidak aktif, mencoba menjadi leader.');
                tryToBecomeLeader();
            }
        }, 5000);
    }

    /**
     * Mencoba menjadi Heartbeat Leader
     */
    function tryToBecomeLeader() {
        if (!isHeartbeatLeader && isTabActive()) {
            becomeHeartbeatLeader();
        }
    }

    /**
     * Menjadi Heartbeat Leader
     */
    function becomeHeartbeatLeader() {
        isHeartbeatLeader = true;
        sessionStorage.setItem('heartbeat_leader', Date.now());

        // Mengirim pesan bahwa leader ada
        broadcastMessage({ type: 'heartbeat_leader_present' });

        // Hitung waktu hingga heartbeat berikutnya
        const timeUntilNext = timeUntilNextHeartbeat();

        // Set timeout untuk heartbeat pertama
        nextHeartbeatTimeout = setTimeout(function () {
            sendHeartbeat('active');
            // Setelah heartbeat pertama, set interval untuk heartbeat selanjutnya
            heartbeatTimer = setInterval(function () {
                sendHeartbeat('active');
            }, heartbeatInterval);
        }, timeUntilNext);

        // console.log('Tab ini menjadi leader dan akan mengirim heartbeat dalam', timeUntilNext, 'ms.');
    }

    /**
     * Resign sebagai Heartbeat Leader
     */
    function resignLeadership() {
        isHeartbeatLeader = false;
        sessionStorage.removeItem('heartbeat_leader');

        // Mengirim pesan bahwa leader tidak ada
        broadcastMessage({ type: 'heartbeat_leader_absent' });

        // Hentikan pengiriman heartbeat
        if (heartbeatTimer) {
            clearInterval(heartbeatTimer);
            heartbeatTimer = null;
        }
        if (nextHeartbeatTimeout) {
            clearTimeout(nextHeartbeatTimeout);
            nextHeartbeatTimeout = null;
        }

        // console.log('Tab ini melepaskan peran leader.');
    }

    /**
     * Memeriksa apakah leader masih aktif
     * @returns {boolean} True jika leader masih aktif, false jika tidak
     */
    function isLeaderAlive() {
        const lastLeaderTime = parseInt(sessionStorage.getItem('heartbeat_leader'), 10);
        if (!lastLeaderTime) return false;

        const now = Date.now();
        // Jika leader tidak meng-update status dalam 35 detik, anggap leader tidak aktif
        return (now - lastLeaderTime) < 35000;
    }

    /**
     * Memeriksa apakah tab saat ini aktif (visible)
     * @returns {boolean} True jika tab aktif, false jika tidak
     */
    function isTabActive() {
        return document.visibilityState === 'visible';
    }

    /**
     * Menangani perubahan visibilitas tab
     */
    function handleVisibilityChange() {
        if (document.visibilityState === 'visible') {
            // console.log('Tab menjadi aktif.');
            // Jika tidak ada leader, coba menjadi leader
            if (!isLeaderAlive()) {
                tryToBecomeLeader();
            }
        } else {
            // console.log('Tab menjadi tidak aktif.');
            // Jika tab ini adalah leader, resign leadership
            if (isHeartbeatLeader) {
                resignLeadership();
            }
        }
    }

    // Mendengarkan perubahan visibilitas tab
    document.addEventListener('visibilitychange', handleVisibilityChange);

    /**
     * Mendengarkan perubahan pada sessionStorage untuk heartbeat_leader
     */
    window.addEventListener('storage', function (event) {
        if (event.key === 'heartbeat_leader') {
            if (!isLeaderAlive()) {
                // Leader sudah tidak aktif, hapus status dan kirim pesan bahwa leader tidak ada
                sessionStorage.removeItem('heartbeat_leader');
                broadcastMessage({ type: 'heartbeat_leader_absent' });
                // console.log('Leader telah dihapus karena tidak aktif.');
            }
        }
    });

    /**
     * Mengirimkan Heartbeat dan mengatur timer saat halaman dimuat
     * Menambahkan delay 1 detik sebelum menginisialisasi heartbeat untuk memastikan flashdata telah diset
     */
    document.addEventListener('DOMContentLoaded', function () {
        // Tambahkan delay 1 detik sebelum menginisialisasi heartbeat
        setTimeout(function () {
            resetInactivityTimer();
            setupActivityListeners();
            manageHeartbeatAcrossTabs();
            // console.log('Heartbeat telah diinisialisasi setelah delay 1 detik.');
        }, 1000);
    });

})();