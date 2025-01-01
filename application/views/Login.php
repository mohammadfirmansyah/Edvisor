<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
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
            <form id="loginForm" action="<?= site_url('formLogin'); ?>" method="POST">
                <div class="alamat-email">
                    <div class="label">Alamat E-mail <span class="email-validity validity-icon"></span></div>
                    <div class="input-field-inner">
                        <div class="placeholder-wrapper">
                            <!-- Input email dengan validasi required -->
                            <input type="email" name="email_address" id="email" class="placeholder1"
                                placeholder="Silakan masukkan alamat E-mail" required>
                        </div>
                    </div>
                </div>
                <div class="kata-sandi">
                    <div class="label">Kata Sandi</div> <!-- Menghapus <span class="password-validity validity-icon"></span> -->
                    <div class="input-field-inner kata-sandi-inner">
                        <div class="placeholder-wrapper">
                            <!-- Input password dengan validasi required -->
                            <input type="password" name="password" id="password" class="placeholder"
                                placeholder="Masukkan Kata Sandi" required>
                        </div>
                        <!-- Icon untuk toggle visibilitas password dengan event onclick -->
                        <img oncontextmenu="return false;" class="kata-sandi-child" alt=""
                            src="assets/img/icon_eye.svg" data-password-id="password">
                    </div>
                </div>

                <!-- Field tersembunyi untuk browser dan mode -->
                <input type="hidden" name="browser" id="browser">
                <input type="hidden" name="mode" id="mode">

                <button disabled type="submit" class="button link" id="buttonContainer">
                    <div class="ini-adalah-text">Masuk</div>
                </button>

                <!-- Checkbox "Ingat Saya" -->
                <label class="ingat-saya">
                    <input type="checkbox" name="remember_me" class="checkbox-input" />
                    <div class="checkbox unselectable-text">
                        <img oncontextmenu="return false;" class="large-ico-check"
                            src="assets/img/icon_checkmark.svg" alt="Checkbox Checkmark">
                    </div>
                    <div class="ingat-saya1 unselectable-text">Ingat saya</div>
                </label>
            </form>
            <a class="text-button link" href="pageLupaKataSandi">
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Mengambil elemen flashdata
            var flashdata = document.getElementById('flashdata');
            var success = flashdata.getAttribute('data-success'); // Mendapatkan data-success
            var error = flashdata.getAttribute('data-error'); // Mendapatkan data-error
            var loginRequired = flashdata.getAttribute('data-login-required');
            var sessionInvalid = flashdata.getAttribute('data-session-invalid');
            var sessionTimeout = flashdata.getAttribute('data-session-timeout');
            var loginError = flashdata.getAttribute('data-login-error');
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
                        confirmButtonColor: '#2563EB',
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

                // Menambahkan pesan login_error ke array jika ada dengan footer yang lebih rapi
                if (loginError) {
                    messages.push({
                        icon: 'error',
                        title: 'Error Login',
                        text: loginError,
                        footer: `
                        <div style="text-align: left; font-size: 14px; color: #555;">
                            <h4 style="margin-bottom: 8px;">Petunjuk:</h4>
                            <ul style="padding-left: 20px; margin: 0;">
                                <li>Anda hanya diperbolehkan masuk menggunakan satu browser.</li>
                                <li>Silakan logout akun Anda pada browser yang lain.</li>
                                <li>Jika Anda sudah menutup aplikasi tanpa melakukan logout, tunggu selama <strong>1 menit</strong> sebelum sistem secara otomatis mengeluarkan Anda.</li>
                            </ul>
                        </div>
                    `,
                        confirmButtonColor: '#2563EB'
                    });
                }
            }

            /**
             * Fungsi untuk menampilkan pesan notifikasi secara bergantian
             *
             * @param {Array} msgs - Array yang berisi objek pesan notifikasi.
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
                        footer: currentMsg.footer || '', // Tambahkan footer jika ada
                        confirmButtonText: 'OK',
                        confirmButtonColor: currentMsg.confirmButtonColor
                    }).then(function () {
                        showNext();
                    });
                }

                showNext();
            }

            // Memanggil fungsi untuk menampilkan pesan notifikasi
            showMessagesSequentially(messages);

            // Mendapatkan elemen input dan tombol
            var emailInput = document.getElementById('email');
            var passwordInput = document.getElementById('password');
            var masukButton = document.getElementById('buttonContainer');
            var typingTimeout; // Variabel untuk timeout saat mengetik

            /**
             * Fungsi untuk memvalidasi format email
             * @param {string} email - Alamat email yang akan divalidasi
             * @returns {boolean} - Mengembalikan true jika email valid, false jika tidak
             */
            function validateEmail(email) {
                var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Pola regex untuk email
                return emailPattern.test(email); // Mengembalikan hasil validasi
            }

            /**
             * Fungsi untuk memvalidasi seluruh form
             */
            function validateForm() {
                // Validasi Alamat E-mail: Tidak kosong dan format valid
                var isEmailValid = emailInput.value.trim() !== '' && validateEmail(emailInput.value);

                // Validasi Kata Sandi: Tidak kosong
                var isPasswordValid = passwordInput.value.trim() !== '';

                // Memeriksa apakah semua validasi terpenuhi
                var isFormComplete = isEmailValid && isPasswordValid;

                // Mengaktifkan atau menonaktifkan tombol masuk berdasarkan validasi form
                masukButton.disabled = !isFormComplete;
            }

            /**
             * Fungsi untuk memperbarui ikon validitas pada field input
             * @param {HTMLElement} inputElement - Elemen input yang divalidasi
             * @param {string} selector - Selector untuk elemen validitas
             * @param {boolean} isValid - Status validitas input
             */
            function updateValidityIcon(inputElement, selector, isValid) {
                var element = document.querySelector(selector); // Mendapatkan elemen berdasarkan selector
                if (inputElement.value.trim() === '') {
                    element.textContent = ''; // Mengosongkan teks jika input kosong
                } else if (isValid) {
                    element.textContent = '(✔)'; // Menampilkan centang jika valid
                    element.style.color = 'green'; // Mengubah warna teks menjadi hijau
                } else {
                    element.textContent = '(✘)'; // Menampilkan silang jika tidak valid
                    element.style.color = 'red'; // Mengubah warna teks menjadi merah
                }
            }

            // Menambahkan event listener pada input email untuk validasi saat mengetik
            emailInput.addEventListener('input', function () {
                validateForm(); // Memvalidasi form
                // Update ikon validitas
                var isValid = validateEmail(emailInput.value);
                updateValidityIcon(emailInput, '.email-validity', isValid);
            });

            // Menambahkan event listener pada input password untuk validasi saat mengetik
            passwordInput.addEventListener('input', function () {
                clearTimeout(typingTimeout); // Membersihkan timeout sebelumnya
                validateForm(); // Memvalidasi form
            });

            // Menambahkan event listener klik pada ikon toggle password
            document.querySelectorAll('[data-password-id]').forEach(function (icon) {
                icon.addEventListener('click', function (e) {
                    e.stopPropagation(); // Mencegah event bubbling ke wrapper
                    var passwordId = this.getAttribute('data-password-id');
                    togglePassword(passwordId, this);
                });
            });

            /**
             * Fungsi untuk toggle visibilitas password
             * @param {string} passwordFieldId - ID dari input field kata sandi
             * @param {HTMLElement} toggleIcon - Elemen ikon toggle
             */
            function togglePassword(passwordFieldId, toggleIcon) {
                var passwordInput = document.getElementById(passwordFieldId); // Mendapatkan input password berdasarkan ID

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text'; // Mengubah tipe input menjadi teks
                    toggleIcon.src = 'assets/img/icon_eye_off.svg'; // Mengubah ikon menjadi eye_off
                } else {
                    passwordInput.type = 'password'; // Mengubah tipe input kembali ke password
                    toggleIcon.src = 'assets/img/icon_eye.svg'; // Mengubah ikon kembali ke eye
                }

                // Fokus ke input password setelah toggle
                passwordInput.focus();
            }

            /**
             * Menambahkan fungsionalitas fokus otomatis pada input saat klik pada input-field-inner
             */
            document.querySelectorAll('.input-field-inner').forEach(function (wrapper) {
                wrapper.addEventListener('click', function (e) {
                    // Prevent triggering if the click is inside the input
                    if (e.target.tagName.toLowerCase() === 'input' || e.target.classList.contains('kata-sandi-child')) {
                        return;
                    }

                    var input = wrapper.querySelector('input');
                    if (input) {
                        input.focus();
                        if (input.value) {
                            // Gunakan setTimeout untuk memastikan setSelectionRange dipanggil setelah fokus diterapkan
                            setTimeout(function () {
                                var len = input.value.length;
                                input.setSelectionRange(len, len);
                            }, 0);
                        }
                    }
                });
            });

            /**
             * Menangani perubahan status checkbox "Ingat Saya"
             */
            var rememberMeCheckbox = document.querySelector('.checkbox-input');
            if (rememberMeCheckbox) {
                rememberMeCheckbox.addEventListener('change', function () {
                    // Anda dapat menambahkan logika tambahan di sini jika diperlukan
                    console.log('Checkbox "Ingat Saya" berubah:', rememberMeCheckbox.checked);
                });
            }

            /**
             * Mendeteksi browser dan mode incognito menggunakan detectIncognito.js
             * dan menyimpannya pada field tersembunyi dalam form.
             * Pastikan Anda telah menyertakan script detectIncognito.js
             */
            if (typeof detectIncognito === 'function') {
                detectIncognito().then((result) => {
                    const browserField = document.getElementById('browser');
                    const modeField = document.getElementById('mode');

                    // Set nilai browser dan mode (private/normal)
                    browserField.value = result.browserName;
                    modeField.value = result.isPrivate ? 'private' : 'normal';
                }).catch((error) => {
                    console.error('Error detecting incognito mode:', error);
                });
            } else {
                console.warn('Library detectIncognito.js tidak ditemukan. Pastikan telah disertakan.');
            }
        });

        // Mendapatkan elemen dengan ID 'psA1TtmLqiU1Image'
        var psA1TtmLqiU1Image = document.getElementById("psA1TtmLqiU1Image");
        // Memeriksa apakah elemen tersebut ada di dalam DOM
        if (psA1TtmLqiU1Image) {
            // Menambahkan event listener untuk klik pada gambar Play Store
            psA1TtmLqiU1Image.addEventListener("click", function () {
                // Membuka tautan Play Store dalam tab baru
                window.open("https://play.google.com/store/apps/details?id=feri.com.lesson");
            });
        }
    </script>
</body>

</html>