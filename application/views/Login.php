<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/login.css" />
    <!-- Sertakan CSS SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <!-- Flashdata Messages sebagai Data Attributes -->
    <?php
    // Mendapatkan flashdata yang akan digunakan untuk menampilkan pesan sukses, error, login_required, session_invalid, session_timeout, login_error, dan notification
    $success_message = isset($success_message) ? $success_message : '';
    $error_message = isset($error_message) ? $error_message : '';
    $login_required_message = isset($login_required) ? $login_required : '';
    $session_invalid_message = isset($session_invalid) ? $session_invalid : '';
    $session_timeout_message = isset($session_timeout) ? $session_timeout : '';
    $login_error_message = isset($login_error) ? $login_error : '';
    $notification = isset($notification) ? $notification : null;
    ?>
    <!-- Menyimpan flashdata sebagai atribut data pada div dengan id 'flashdata' -->
    <div id="flashdata"
        data-success="<?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-error="<?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-login-required="<?= htmlspecialchars($login_required_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-session-invalid="<?= htmlspecialchars($session_invalid_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-session-timeout="<?= htmlspecialchars($session_timeout_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-login-error="<?= htmlspecialchars($login_error_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-notification="<?= $notification ? htmlspecialchars(json_encode($notification), ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <div class="login unselectable-text">
        <div class="sinkronisasi-aplikasi-mobile-parent">
            <div class="sinkronisasi-aplikasi-mobile">Sinkronisasi Aplikasi Mobile dan Web</div>
            <img oncontextmenu="return false;" class="illustrasi-sinkronisasi-icon" alt="" src="assets/img/illustrasi_sinkronisasi.svg">
            <div class="tersedia-juga">
                <div class="tersedia-juga-di">Tersedia juga di:</div>
                <img oncontextmenu="return false;" class="psa1ttmlqiu-1-icon" alt="" src="assets/img/icon_play_store.svg" id="psA1TtmLqiU1Image">
            </div>
        </div>
        <div class="login-box">
            <div class="logo-ls-1-parent">
                <img oncontextmenu="return false;" class="logo-ls-1" alt="" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </div>
            <div class="masuk-akun">Masuk Akun</div>
            <div class="jika-sudah-terdaftar">Jika sudah terdaftar, silakan masuk ke akun Anda</div>
            <form action="<?= site_url('formLogin'); ?>" method="POST">
                <div class="alamat-email">
                    <div class="label">Alamat E-mail</div>
                    <div class="input-field-inner">
                        <div class="placeholder-wrapper">
                            <input type="email" name="email_address" id="email" class="placeholder1" placeholder="Silakan masukkan alamat E-mail" required>
                        </div>
                    </div>
                </div>
                <div class="kata-sandi">
                    <div class="label">Kata Sandi</div>
                    <div class="input-field-inner">
                        <div class="placeholder-wrapper">
                            <input type="password" name="password" id="password" class="placeholder" placeholder="Masukkan Kata Sandi" required>
                        </div>
                        <img oncontextmenu="return false;" class="kata-sandi-child" alt="" src="assets/img/icon_eye.svg" onclick="togglePassword()">
                    </div>
                </div>

                <button type="submit" class="button link">
                    <div class="ini-adalah-text">Masuk</div>
                </button>

                <!-- Checkbox "Ingat Saya" -->
                <label class="ingat-saya">
                    <input type="checkbox" name="remember_me" class="checkbox-input" />
                    <div class="checkbox unselectable-text">
                        <img oncontextmenu="return false;" class="large-ico-check" src="assets/img/icon_checkmark.svg" alt="Checkbox Checkmark">
                    </div>
                    <div class="ingat-saya1 unselectable-text">Ingat saya</div>
                </label>
            </form>
            <a class="text-button link" href="pageLupaPassword">
                <div class="ini-adalah-text1">Lupa Kata Sandi?</div>
            </a>
            <div class="belum-punya-akun">Belum punya akun?</div>
            <div class="login-child">
            </div>
            <a class="text-button1 link" href="pageSignup">
                <div class="ini-adalah-text1">Daftar Disini</div>
            </a>
        </div>
    </div>
</body>

<!-- Sertakan jQuery dan SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Menunggu hingga halaman selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        // Mengambil elemen dengan id 'flashdata'
        var flashdata = document.getElementById('flashdata');

        // Mengambil data-notification
        var notificationData = flashdata.getAttribute('data-notification');

        // Array untuk menyimpan semua pesan notifikasi
        var messages = [];

        if (notificationData) {
            try {
                var notification = JSON.parse(notificationData);
                if (notification.title && notification.text && notification.icon) {
                    messages.push({
                        icon: notification.icon,
                        title: notification.title,
                        text: notification.text,
                        confirmButtonColor: '#2563EB'
                    });
                }
            } catch (e) {
                console.error('Error parsing notification data:', e);
            }
        } else {
            // Jika tidak ada notification, proses flashdata lainnya
            var success = flashdata.getAttribute('data-success');
            var error = flashdata.getAttribute('data-error');
            var loginRequired = flashdata.getAttribute('data-login-required');
            var sessionInvalid = flashdata.getAttribute('data-session-invalid');
            var sessionTimeout = flashdata.getAttribute('data-session-timeout');
            var loginError = flashdata.getAttribute('data-login-error');

            // Menambahkan pesan sukses ke array jika ada
            if (success) {
                messages.push({
                    icon: 'success',
                    title: 'Berhasil',
                    text: success,
                    confirmButtonColor: '#2563EB'
                });
            }

            // Menambahkan pesan error umum ke array jika ada
            if (error) {
                messages.push({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: error,
                    confirmButtonColor: '#2563EB'
                });
            }

            // Menambahkan pesan login_required ke array jika ada
            if (loginRequired) {
                messages.push({
                    icon: 'warning',
                    title: 'Akses Ditolak',
                    text: loginRequired,
                    confirmButtonColor: '#2563EB'
                });
            }

            // Menambahkan pesan session_invalid ke array jika ada
            if (sessionInvalid) {
                messages.push({
                    icon: 'info',
                    title: 'Sesi Tidak Valid',
                    text: sessionInvalid,
                    confirmButtonColor: '#2563EB'
                });
            }

            // Menambahkan pesan session_timeout ke array jika ada
            if (sessionTimeout) {
                messages.push({
                    icon: 'warning',
                    title: 'Sesi Habis',
                    text: sessionTimeout,
                    confirmButtonColor: '#F59E0B'
                });
            }

            // Menambahkan pesan login_error ke array jika ada
            if (loginError) {
                messages.push({
                    icon: 'error',
                    title: 'Error Login',
                    text: loginError,
                    confirmButtonColor: '#2563EB'
                });
            }
        }

        /**
         * Fungsi untuk menampilkan pesan notifikasi secara bergantian
         *
         * @param {Array} msgs Array yang berisi objek pesan notifikasi
         */
        function showMessagesSequentially(msgs) {
            if (msgs.length === 0) return;

            // Clone array untuk menghindari modifikasi array asli
            var queue = msgs.slice();

            /**
             * Fungsi rekursif untuk menampilkan pesan satu per satu
             */
            function showNext() {
                if (queue.length === 0) return;

                var currentMsg = queue.shift();
                Swal.fire({
                    icon: currentMsg.icon,
                    title: currentMsg.title,
                    text: currentMsg.text,
                    confirmButtonText: 'OK',
                    confirmButtonColor: currentMsg.confirmButtonColor
                }).then(function() {
                    showNext();
                });
            }

            showNext();
        }

        // Memanggil fungsi untuk menampilkan pesan notifikasi
        showMessagesSequentially(messages);
    });

    // Menangani klik pada ikon Play Store untuk membuka aplikasi di Google Play Store
    var psA1TtmLqiU1Image = document.getElementById("psA1TtmLqiU1Image");
    if (psA1TtmLqiU1Image) {
        psA1TtmLqiU1Image.addEventListener("click", function() {
            window.open("https://play.google.com/store/apps/details?id=feri.com.lesson");
        });
    }

    // Fungsi untuk menutup pesan sukses jika diperlukan
    function closeMessage() {
        var messageDiv = document.getElementById('successMessage');
        if (messageDiv) {
            messageDiv.style.display = 'none';
        }
    }

    // Fungsi untuk menampilkan atau menyembunyikan kata sandi pada input
    function togglePassword() {
        var passwordInput = document.getElementById('password');
        var toggleIcon = document.querySelector('.kata-sandi-child');

        // Mengubah jenis input dari password menjadi text untuk menunjukkan kata sandi
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.src = 'assets/img/icon_eye_off.svg'; // Ganti ikon mata tertutup
        } else {
            passwordInput.type = 'password';
            toggleIcon.src = 'assets/img/icon_eye.svg'; // Ganti ikon mata terbuka
        }
    }

    // Menangani perubahan status checkbox "Ingat Saya"
    document.addEventListener("DOMContentLoaded", function() {
        var checkbox = document.querySelector('.checkbox-input'); // Memilih checkbox berdasarkan kelas
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                // Log jika checkbox dicentang atau tidak dicentang
                if (checkbox.checked) {
                    // console.log('Checkbox dicentang');
                } else {
                    // console.log('Checkbox tidak dicentang');
                }
            });
        }
    });

    // Mengatur fokus pada input email setelah halaman selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        var emailInput = document.getElementById('email');

        // Fungsi untuk mengatur fokus pada input email setelah delay
        function setFocus() {
            setTimeout(function() {
                emailInput.focus();
            }, 300); // Menambahkan delay untuk memastikan modal sudah muncul sebelum fokus
        }

        setFocus();
    });
</script>

</html>