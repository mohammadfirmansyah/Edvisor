/**
 * heartbeat.js
 *
 * Mengelola pengiriman heartbeat secara periodik dan deteksi inaktivitas pengguna.
 * Menggunakan Axios untuk permintaan HTTP, BroadcastChannel atau fallback localStorage untuk sinkronisasi antar tab,
 * serta strategi event visibilitychange dan sendBeacon untuk mengirim status 'wait' pada saat tab ditutup atau di-refresh
 * tanpa mengganggu flashdata.
 */

(function () {
    // Pastikan Axios tersedia
    if (typeof axios === 'undefined') {
        console.error('Axios tidak ditemukan. Pastikan untuk menyertakan Axios sebelum heartbeat.js.');
        return;
    }

    // Pastikan SweetAlert2 (Swal) tersedia
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 tidak ditemukan. Pastikan untuk menyertakan SweetAlert2 sebelum heartbeat.js.');
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
    let reconnectDeadline = null; // Timestamp deadline reconnect
    const disconnectTimeoutDuration = 30000; // Durasi timeout disconnect dalam milidetik (30 detik)

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
                            sendHeartbeatWithRetry('active').catch((error) => {
                                console.error('Heartbeat gagal dikirim setelah penutupan tab:', error);
                                // Tidak memanggil showReconnectDialog di sini karena akan ditangani oleh sendHeartbeatWithRetry
                            });
                            console.log('Leader mengirim heartbeat active setelah 1 detik penutupan tab.');
                        }
                    }, 1000);
                }
                break;
            case 'session_regenerated':
                handleSessionRegeneration();
                break;
            default:
                break;
        }
    }

    /**
     * Menangani regenerasi sesi yang dikirim dari tab leader.
     */
    function handleSessionRegeneration() {
        console.log('Menerima notifikasi regenerasi sesi. Memperbarui sesi.');
        // Anda bisa menambahkan logika tambahan di sini, seperti memperbarui variabel sesi atau mereset state
        // Misalnya, mengirim ulang heartbeat secara manual
        if (isHeartbeatLeader) {
            // Leader mungkin perlu memperbarui status sesi
            sendHeartbeatWithRetry('active').then(() => {
                console.log('Heartbeat berhasil dikirim setelah regenerasi sesi.');
            }).catch((error) => {
                console.error('Heartbeat gagal dikirim setelah regenerasi sesi:', error);
                showReconnectDialog();
            });
        } else {
            // Non-leader tab dapat memilih untuk melakukan reload untuk mendapatkan sesi terbaru
            console.log('Non-leader tab memuat ulang halaman untuk mendapatkan sesi terbaru.');
            window.location.reload();
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

        // Set reconnectDeadline hanya sekali ketika koneksi terputus
        if (!reconnectDeadline) {
            reconnectDeadline = Date.now() + disconnectTimeoutDuration;
        }

        showReconnectPopup();
    }

    /**
     * Menampilkan popup reconnect dengan sisa waktu yang ditentukan menggunakan timer bawaan SweetAlert2.
     */
    function showReconnectPopup() {
        const remainingTime = reconnectDeadline - Date.now();
        if (remainingTime <= 0) {
            window.location.href = 'sidebarLogout?reason=disconnect';
            return;
        }

        const styleElement = document.createElement('style');
        styleElement.textContent = `
                .swal2-container {
                    z-index: 9999 !important; /* Sesuaikan dengan kebutuhan Anda */
                }
                `;
        document.head.appendChild(styleElement);

        Swal.fire({
            title: 'Koneksi Terputus',
            text: 'Tidak dapat terhubung ke server. Silakan coba reconnect.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Reconnect',
            cancelButtonText: 'Logout Sekarang',
            allowOutsideClick: false,
            allowEscapeKey: false,
            timer: remainingTime,
            timerProgressBar: true,
            didOpen: () => {
                // Tidak perlu menampilkan timer via teks, gunakan bawaan SweetAlert2
            },
            willClose: (reason) => {
                if (reason === 'timer') {
                    window.location.href = 'sidebarLogout?reason=disconnect';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                attemptReconnect();
            } else {
                window.location.href = 'sidebarLogout?reason=disconnect';
            }
        });
    }

    /**
     * Menyembunyikan dialog reconnect.
     */
    function hideReconnectDialog() {
        if (!isDisconnected) return;
        isDisconnected = false;

        // Reset reconnectDeadline
        reconnectDeadline = null;

        // Tutup popup SweetAlert2
        setTimeout(() => {
            Swal.close();
        }, 0);
        broadcastMessage({ type: 'hide_reconnect' });
    }

    /**
     * Mencoba untuk reconnect dengan mengirim heartbeat.
     * Menampilkan loading SweetAlert2 hingga heartbeat berhasil atau gagal.
     * Jika reconnect gagal, popup reconnect akan ditampilkan kembali dengan sisa waktu yang telah berkurang.
     */
    async function attemptReconnect() {
        Swal.fire({
            title: 'Menyambungkan Ulang...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            await sendHeartbeatWithRetry('active');
            Swal.close();
            hideReconnectDialog();
        } catch (error) {
            Swal.close();
            console.error('Reconnect gagal:', error);
            // Re-show reconnect popup dengan sisa waktu yang telah berkurang
            showReconnectPopup();
        }
    }

    /**
     * Mengirim heartbeat ke server dengan status tertentu.
     * @param {string} [status='active'] - Status heartbeat yang dikirim ('active' atau 'wait').
     * @returns {Promise} - Promise yang diselesaikan saat heartbeat berhasil atau gagal.
     */
    function sendHeartbeat(status = 'active') {
        // Menambahkan 'return' agar bisa menggunakan then/catch di pemanggilannya.
        return axios.post('updateActivity', new URLSearchParams({ status: status }), {
            // Pastikan kredensial disertakan jika diperlukan
            withCredentials: true,
            // Tambahkan headers jika diperlukan, misalnya CSRF token
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            timeout: 10000 // Tambahkan timeout untuk menghindari permintaan yang menggantung
        })
            .then(function (response) {
                // Cek apakah response headers mengindikasikan JSON
                const contentType = response.headers['content-type'];
                if (!contentType || !contentType.includes('application/json')) {
                    console.error('Respons tidak berformat JSON:', response);
                    return Promise.reject(new Error('Invalid response format'));
                }

                const data = response.data;

                if (typeof data === 'object' && data !== null && data.status === 'success') {
                    console.log('Heartbeat dikirim:', data);
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
                } else if (typeof data === 'object' && data !== null && data.status === 'error' && data.message === 'Terjadi kesalahan pada sistem. Silakan coba lagi.') {
                    // Menangani kasus sesi tidak valid atau kedaluwarsa
                    console.warn('Sesi tidak valid atau kedaluwarsa.');
                    broadcastMessage({ type: 'session_regenerated' });
                    return Promise.reject(new Error('Terjadi kesalahan pada sistem. Silakan coba lagi.'));
                } else {
                    // Respons bukan JSON atau status tidak sukses, tangani sebagai kegagalan
                    console.error('Heartbeat gagal dikirim: Status tidak sukses.', data);
                    return Promise.reject(new Error('Heartbeat gagal: Status tidak sukses'));
                }
            })
            .catch(function (error) {
                console.error('Gagal mengirim Heartbeat:', error);

                // Cek apakah respons adalah JSON
                if (error.response) {
                    const contentType = error.response.headers['content-type'];
                    if (contentType && contentType.includes('application/json')) {
                        const data = error.response.data;
                        console.error('Error dari server:', data);
                    } else {
                        console.error('Error non-JSON diterima:', error.response.data);
                    }
                } else if (error.request) {
                    // Permintaan dibuat tetapi tidak ada respons yang diterima
                    console.error('Tidak ada respons diterima:', error.request);
                } else {
                    // Terjadi kesalahan saat menyiapkan permintaan
                    console.error('Kesalahan dalam setup permintaan:', error.message);
                }

                // Jika error adalah karena sesi invalid atau expired, broadcast regenerasi sesi
                if (error.response && error.response.data && error.response.data.message === 'Terjadi kesalahan pada sistem. Silakan coba lagi.') {
                    broadcastMessage({ type: 'session_regenerated' });
                }

                return Promise.reject(error);
            });
    }

    /**
     * Mengirim heartbeat dengan mekanisme retry.
     * Mencoba mengirim heartbeat hingga 10 kali dengan delay 0.5 detik antar retry.
     * Jika semua retry gagal, tampilkan pop-up reconnect.
     * @param {string} [status='active'] - Status heartbeat yang dikirim ('active' atau 'wait').
     * @param {number} [maxRetries=10] - Jumlah maksimal retry.
     * @param {number} [retryDelay=500] - Delay antar retry dalam milidetik.
     * @returns {Promise} - Promise yang diselesaikan saat heartbeat berhasil atau semua retry gagal.
     */
    function sendHeartbeatWithRetry(status = 'active', maxRetries = 10, retryDelay = 500) {
        return new Promise((resolve, reject) => {
            let attempts = 0;

            const attemptSend = () => {
                sendHeartbeat(status)
                    .then(resolve)
                    .catch((error) => {
                        attempts++;
                        console.warn(`Heartbeat attempt ${attempts} gagal.`);

                        if (attempts < maxRetries) {
                            setTimeout(attemptSend, retryDelay);
                        } else {
                            // Setelah mencapai maksimal retry, tampilkan reconnect popup
                            console.error(`Heartbeat gagal setelah ${maxRetries} kali percobaan.`);
                            reject(error);
                        }
                    });
            };

            attemptSend();
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

        // Jika halaman kembali visible namun isClosing sempat true, reset isClosing
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
        // Cek setiap 1 detik apakah leader masih aktif
        setInterval(function () {
            if (!isLeaderAlive()) {
                tryToBecomeLeader();
            }
        }, 1000);
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
     * 
     * Perbaikan: Menambahkan mekanisme retry pada pengiriman heartbeat tanpa menampilkan popup reconnect.
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

        if (isUnloading) return;

        isHeartbeatLeader = true;
        broadcastMessage({ type: 'heartbeat_leader_present', tabId: tabId });

        // Mengirim heartbeat dan menangani kegagalan dengan retry
        sendHeartbeatWithRetry('active')
            .then(() => {
                console.log('Heartbeat berhasil dikirim saat menjadi leader.');
            })
            .catch((error) => {
                console.error('Heartbeat gagal dikirim saat menjadi leader:', error);
                // Tidak memanggil showReconnectDialog di sini karena akan dipanggil setelah semua retry gagal
                showReconnectDialog();
            });

        // Atur interval untuk mengirim heartbeat secara berkala dengan penanganan kegagalan
        heartbeatTimer = setInterval(function () {
            if (!isUnloading) {
                sendHeartbeatWithRetry('active')
                    .then(() => {
                        console.log('Heartbeat berhasil dikirim selama interval.');
                    })
                    .catch((error) => {
                        console.error('Heartbeat gagal dikirim selama interval:', error);
                        // Tidak memanggil showReconnectDialog di sini karena akan dipanggil setelah semua retry gagal
                        showReconnectDialog();
                    });
            }
        }, heartbeatInterval);
        console.log('Tab ini menjadi leader dan mengirim heartbeat active.');
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
     */
    document.addEventListener('DOMContentLoaded', function () {
        resetInactivityTimer();
        setupActivityListeners();
        manageHeartbeatAcrossTabs();
    });

    /**
     * Menangani regenerasi sesi dengan memberitahu semua tab.
     */
    function handleSessionRegeneration() {
        console.log('Regenerasi sesi terdeteksi. Memperbarui sesi di semua tab.');
        // Broadcast pesan bahwa sesi telah diregenerasi
        broadcastMessage({ type: 'session_regenerated' });

        // Leader harus mengatur ulang sesi
        if (isHeartbeatLeader) {
            sendHeartbeatWithRetry('active')
                .then(() => {
                    console.log('Heartbeat berhasil dikirim setelah regenerasi sesi.');
                })
                .catch((error) => {
                    console.error('Heartbeat gagal dikirim setelah regenerasi sesi:', error);
                    showReconnectDialog();
                });
        }
    }

    /**
     * Mendengarkan perubahan pada cookie sesi dan menangani regenerasi sesi.
     */
    function monitorSessionCookie() {
        let lastSessionId = getSessionIdFromCookie();

        setInterval(() => {
            const currentSessionId = getSessionIdFromCookie();
            if (currentSessionId !== lastSessionId) {
                lastSessionId = currentSessionId;
                console.log('Perubahan sesi terdeteksi.');
                handleSessionRegeneration();
            }
        }, 1000); // Periksa setiap detik
    }

    /**
     * Mendapatkan ID sesi dari cookie.
     * @returns {string|null} - ID sesi atau null jika tidak ditemukan.
     */
    function getSessionIdFromCookie() {
        const name = 'ci_session=';
        const decodedCookie = decodeURIComponent(document.cookie);
        const ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return null;
    }

    // Mulai memonitor perubahan sesi
    monitorSessionCookie();

})();