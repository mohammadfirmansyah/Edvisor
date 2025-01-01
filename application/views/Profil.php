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
    // Mendapatkan flashdata dari sesi
    $success_message = $this->session->flashdata('success');
    $error_message = $this->session->flashdata('error');
    ?>
    <div id="flashdata"
        data-success="<?= isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-error="<?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <div class="profil unselectable-text">
        <!-- Sidebar navigasi -->
        <div class="sidebar">
            <!-- Logo Lesson Study dan link ke beranda -->
            <a class="logo-ls-1-parent link" href="sidebarBeranda">
                <img oncontextmenu="return false;" class="logo-ls-1" alt="" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </a>
            <!-- Link ke profil pengguna -->
            <a class="profile-side-bar link" href="sidebarProfile">
                <div class="profile-side-bar-child" alt="">
                    <img oncontextmenu="return false;" class="profile-photo"
                        src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>"
                        alt="">
                </div>
                <div class="nama-pengguna"><?= htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="email-pengguna"><?= htmlspecialchars($user->email_address, ENT_QUOTES, 'UTF-8'); ?></div>
            </a>
            <!-- Menu navigasi utama -->
            <div class="menu-bar">
                <a class="item-side-bar-default link" href="sidebarBeranda">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt=""
                        src="assets/img/icon_beranda.svg">
                    <div class="text-sidebar-default">Beranda</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarGuruModel">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt=""
                        src="assets/img/icon_guru_model.svg">
                    <div class="text-sidebar-default">Guru Model</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarObserver">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt=""
                        src="assets/img/icon_observer.svg">
                    <div class="text-sidebar-default">Observer</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarBantuan">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt=""
                        src="assets/img/icon_bantuan.svg">
                    <div class="text-sidebar-default">Bantuan</div>
                </a>
            </div>
            <!-- Link untuk keluar dari akun -->
            <a class="item-side-bar-exit link" href="sidebarLogout">
                <img oncontextmenu="return false;" class="icon-sidebar-default" alt=""
                    src="assets/img/icon_keluar.svg">
                <div class="text-sidebar-default">Keluar</div>
            </a>
        </div>
        <!-- Navigasi kembali ke beranda -->
        <a class="profil-saya-parent link" href="sidebarBeranda">
            <img oncontextmenu="return false;" class="iconsolidarrow-left" alt=""
                src="assets/img/icon_arrow_left.svg">
            <div class="profil-saya">Profil Saya</div>
        </a>
        <!-- Kontainer untuk menampilkan tanggal dan waktu -->
        <div class="date-container">
            <p id="dateDisplay" class="date"></p>
            <p id="timeDisplay" class="date"></p>
        </div>
        <!-- Bagian detail profil pengguna -->
        <div class="detail-profil-parent">
            <div class="detail-profil">Detail Profil</div>
            <!-- Form untuk memperbarui data pengguna -->
            <form id="uploadForm" enctype="multipart/form-data" action="formPerbaruiDataPengguna" method="POST">
                <div class="input-field-parent">
                    <img oncontextmenu="return false;" class="group-child" alt="Foto Profil"
                        src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>">
                    <!-- Menampilkan foto profil pengguna, jika ada -->
                    <div class="profile-photo-container">
                        <!-- Progress Bar Overlay -->
                        <div class="progress-overlay" id="progressOverlay">
                            <div class="progress-bar" id="progressBar"></div>
                        </div>
                    </div>

                    <!-- Input file untuk mengupload foto profil -->
                    <input type="file" id="fileInput" name="profile_photo" accept="image/png, image/jpeg"
                        style="display: none;" />

                    <!-- Input hidden untuk menyimpan data gambar yang telah di-crop -->
                    <input type="hidden" id="croppedImage" name="croppedImage">

                    <!-- Icon kamera yang dapat diklik untuk membuka dialog upload -->
                    <div class="iconsolidcamera" onclick="document.getElementById('fileInput').click();">
                        <img oncontextmenu="return false;" src="assets/img/icon_camera.svg" alt="camera"
                            class="icon-camera">
                        <img oncontextmenu="return false;" src="assets/img/icon_upload_profile.svg" alt="upload"
                            class="icon-upload">
                    </div>

                    <!-- Input untuk Nama Lengkap -->
                    <div class="input-field">
                        <div class="label">Nama Lengkap <span class="nama-validity validity-icon"></span></div>
                        <div class="input-field-inner">
                            <div class="text-filled-wrapper">
                                <input type="text" id="full_name" name="full_name" class="text-filled1"
                                    placeholder="Silakan masukkan nama lengkap"
                                    value="<?php echo htmlspecialchars(isset($user->full_name) ? $user->full_name : ''); ?>"
                                    maxlength="255">
                            </div>
                        </div>
                    </div>

                    <!-- Input untuk Email -->
                    <div class="input-field1">
                        <div class="label">Email <span class="email-validity validity-icon"></span></div>
                        <div class="input-field-inner">
                            <div class="text-filled-wrapper">
                                <input type="email" id="email_address" name="email_address" class="text-filled1"
                                    placeholder="Silakan masukkan alamat E-mail"
                                    value="<?php echo htmlspecialchars(isset($user->email_address) ? $user->email_address : ''); ?>"
                                    maxlength="255">
                            </div>
                        </div>
                    </div>

                    <!-- Input untuk Nomor Induk -->
                    <div class="input-field2">
                        <div class="label">Nomor Induk <span class="registration-validity validity-icon"></span></div>
                        <div class="input-field-inner">
                            <div class="text-filled-wrapper">
                                <input type="text" id="registration_number" name="registration_number"
                                    class="text-filled1" placeholder="Silakan masukkan Nomor Induk"
                                    value="<?php echo htmlspecialchars(isset($user->registration_number) ? $user->registration_number : ''); ?>"
                                    maxlength="255">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tombol untuk menyimpan perubahan -->
                <button type="submit" class="button link" id="saveChangesButton" disabled>
                    <div class="button1">Simpan Perubahan</div>
                </button>
            </form>
        </div>
        <!-- Form untuk memperbarui kata sandi -->
        <form id="passwordForm" enctype="multipart/form-data" action="formPerbaruiPasswordPengguna" method="POST">
            <div class="perbaru-kata-sandi-parent">
                <div class="perbaru-kata-sandi">Perbarui Kata Sandi</div>

                <!-- Kata Sandi Lama -->
                <div class="kata-sandi">
                    <div class="label">Kata Sandi Lama</div>
                    <div class="input-field-inner">
                        <div class="text-filled-wrapper">
                            <input type="password" id="oldPassword" name="current_password" class="text-filled"
                                placeholder="Masukkan Kata Sandi Lama" maxlength="255">
                        </div>
                        <!-- Icon untuk toggle visibilitas kata sandi -->
                        <img oncontextmenu="return false;" class="kata-sandi-child" alt="Toggle Password"
                            src="assets/img/icon_eye.svg" id="toggleOldPassword">
                    </div>
                </div>

                <!-- Kata Sandi Baru -->
                <div class="regis-kata-sandi">
                    <div class="label">Kata Sandi Baru <span class="password-validity validity-icon"></span></div>
                    <div class="input-field-inner input-field-inner1">
                        <div class="text-filled-wrapper">
                            <input type="password" id="newPassword" name="new_password" class="text-filled1"
                                placeholder="Masukkan Kata Sandi Baru" maxlength="255">
                        </div>
                        <!-- Icon untuk toggle visibilitas kata sandi -->
                        <img oncontextmenu="return false;" class="kata-sandi-child" alt="Toggle Password"
                            src="assets/img/icon_eye.svg" id="toggleNewPassword">
                    </div>
                    <!-- Informasi keamanan kata sandi -->
                    <div class="password-security">
                        <div class="security-warning">
                            <img oncontextmenu="return false;" class="icon" alt="Security Icon"
                                src="assets/img/icon_shield_warning.svg">
                            <div class="terdiri-dari-minimal">Terdiri dari minimal 8 karakter</div>
                        </div>
                        <div class="security-warning1">
                            <img oncontextmenu="return false;" class="icon1" alt="Security Icon"
                                src="assets/img/icon_shield_warning.svg">
                            <div class="terdiri-dari-minimal1">Mengandung setidaknya 1 huruf kapital</div>
                        </div>
                        <div class="security-warning2">
                            <img oncontextmenu="return false;" class="icon2" alt="Security Icon"
                                src="assets/img/icon_shield_warning.svg">
                            <div class="terdiri-dari-minimal2">Mengandung setidaknya 1 karakter khusus (misalnya: !, @, #, $, dll.).
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ulangi Kata Sandi Baru -->
                <div class="kata-sandi1">
                    <div class="label">Ulangi Kata Sandi Baru <span class="password-match-validity validity-icon"></span></div>
                    <div class="input-field-inner">
                        <div class="text-filled-wrapper">
                            <input type="password" id="repeatPassword" name="confirm_password" class="text-filled1"
                                placeholder="Ulangi Kata Sandi Baru" maxlength="255">
                        </div>
                        <!-- Icon untuk toggle visibilitas kata sandi -->
                        <img oncontextmenu="return false;" class="kata-sandi-child" alt="Toggle Password"
                            src="assets/img/icon_eye.svg" id="toggleRepeatPassword">
                    </div>
                </div>

                <!-- Tombol Submit untuk memperbarui kata sandi -->
                <button type="submit" class="button2 link" id="updatePasswordButton" disabled>
                    <div class="button1">Perbarui Kata Sandi</div>
                </button>
            </div>
        </form>
    </div>

    <!-- Progress Overlay untuk Upload Foto Profil -->
    <div class="progress-overlay" id="progressOverlay">
        <div class="progress-bar" id="progressBar"></div>
    </div>

    <script>
        // Variabel global untuk menyimpan status upload foto profil
        let isProfilePhotoUploaded = <?php echo !empty($user->src_profile_photo) ? 'true' : 'false'; ?>;

        // Menyimpan nilai awal dari data profil
        let initialFullName = '';
        let initialEmail = '';
        let initialRegistrationNumber = '';
        let initialProfilePhoto = '';

        // Menampilkan pesan flashdata (sukses atau error) menggunakan SweetAlert2 setelah halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            // Mendapatkan elemen flashdata
            var flashdata = document.getElementById('flashdata');
            var success = flashdata.getAttribute('data-success'); // Mendapatkan data-success
            var error = flashdata.getAttribute('data-error'); // Mendapatkan data-error

            // Menampilkan SweetAlert untuk pesan sukses jika ada
            if (success) {
                Swal.fire({
                    icon: 'success', // Ikon sukses
                    title: 'Berhasil', // Judul alert
                    text: success, // Isi pesan sukses
                    confirmButtonText: 'OK', // Teks tombol konfirmasi
                    confirmButtonColor: '#2563EB' // Warna tombol konfirmasi
                });
            }

            // Menampilkan SweetAlert untuk pesan error jika ada
            if (error) {
                Swal.fire({
                    icon: 'error', // Ikon error
                    title: 'Terjadi Kesalahan', // Judul alert
                    text: error, // Isi pesan error
                    confirmButtonText: 'OK', // Teks tombol konfirmasi
                    confirmButtonColor: '#2563EB' // Warna tombol konfirmasi
                });
            }

            // Menyimpan nilai awal dari data profil
            const fullNameInput = document.getElementById('full_name');
            const emailInput = document.getElementById('email_address');
            const registrationNumberInput = document.getElementById('registration_number');
            const croppedImageInput = document.getElementById('croppedImage');

            initialFullName = fullNameInput.value.trim();
            initialEmail = emailInput.value.trim();
            initialRegistrationNumber = registrationNumberInput.value.trim();
            initialProfilePhoto = croppedImageInput.value.trim();

            // Batasi input "Nomor Induk" hanya untuk angka
            registrationNumberInput.addEventListener('input', function() {
                // Hapus semua karakter non-digit
                this.value = this.value.replace(/\D/g, '');
                validateForm();
            });

            // Menambahkan event listeners untuk validasi hanya pada event 'input'
            fullNameInput.addEventListener('input', validateForm);
            emailInput.addEventListener('input', validateForm);
            registrationNumberInput.addEventListener('input', validateForm);
        });

        /**
         * Fungsi untuk toggle visibilitas password
         * @param {string} passwordFieldId - ID dari input field kata sandi
         * @param {string} toggleIconId - ID dari ikon toggle
         */
        function togglePassword(passwordFieldId, toggleIconId) {
            var passwordInput = document.getElementById(passwordFieldId);
            var toggleIcon = document.getElementById(toggleIconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.src = 'assets/img/icon_eye_off.svg';
            } else {
                passwordInput.type = 'password';
                toggleIcon.src = 'assets/img/icon_eye.svg';
            }
        }

        // Menambahkan event listener untuk setiap toggle password
        document.getElementById('toggleOldPassword').addEventListener('click', function() {
            togglePassword('oldPassword', 'toggleOldPassword');
        });

        document.getElementById('toggleNewPassword').addEventListener('click', function() {
            togglePassword('newPassword', 'toggleNewPassword');
        });

        document.getElementById('toggleRepeatPassword').addEventListener('click', function() {
            togglePassword('repeatPassword', 'toggleRepeatPassword');
        });

        /**
         * Fungsi untuk memperbarui ikon validitas
         * @param {HTMLElement} inputElement - Elemen input yang divalidasi
         * @param {string} selector - Selector untuk elemen yang menampilkan ikon
         * @param {boolean} isValid - Status validasi
         */
        function updateValidityIcon(inputElement, selector, isValid) {
            const element = document.querySelector(selector);
            if (!element) return; // Pastikan elemen ada

            // Cek apakah selector adalah untuk kata sandi baru atau ulangi kata sandi baru
            const isPasswordField = selector === '.password-validity' || selector === '.password-match-validity';

            if (isPasswordField && inputElement.value.trim() === '') {
                // Jika ini adalah kata sandi baru atau ulangi kata sandi baru dan input kosong, hapus simbol
                element.textContent = '';
                element.style.color = '';
            } else if (inputElement.value.trim() === '') {
                // Untuk field lain, tetap tampilkan (✘) jika kosong
                element.textContent = '(✘)';
                element.style.color = 'red';
            } else if (isValid) {
                element.textContent = '(✔)';
                element.style.color = 'green';
            } else {
                element.textContent = '(✘)';
                element.style.color = 'red';
            }
        }

        /**
         * Fungsi untuk memvalidasi Nama Lengkap
         * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
         */
        function validateFullName() {
            const fullNameInput = document.getElementById('full_name');
            const name = fullNameInput.value.trim();
            const isValid = name !== '' && !/\d/.test(name) && name.length <= 255;
            updateValidityIcon(fullNameInput, '.nama-validity', isValid);
            return isValid;
        }

        /**
         * Fungsi untuk memvalidasi Email
         * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
         */
        function validateEmailAddress() {
            const emailInput = document.getElementById('email_address');
            const email = emailInput.value.trim();
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const isValid = emailPattern.test(email) && email.length <= 255;
            updateValidityIcon(emailInput, '.email-validity', isValid);
            return isValid;
        }

        /**
         * Fungsi untuk memvalidasi Nomor Induk
         * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
         */
        function validateRegistrationNumber() {
            const registrationNumberInput = document.getElementById('registration_number');
            const number = registrationNumberInput.value.trim();
            const isValid = /^\d+$/.test(number) && number.length > 0 && number.length <= 255;
            updateValidityIcon(registrationNumberInput, '.registration-validity', isValid);
            return isValid;
        }

        /**
         * Fungsi untuk memvalidasi seluruh form
         */
        function validateForm() {
            const fullNameInput = document.getElementById('full_name');
            const emailInput = document.getElementById('email_address');
            const registrationNumberInput = document.getElementById('registration_number');
            const croppedImageInput = document.getElementById('croppedImage');
            const saveChangesButton = document.getElementById('saveChangesButton');

            const isFullNameValid = validateFullName();
            const isEmailValid = validateEmailAddress();
            const isRegistrationNumberValid = validateRegistrationNumber();
            // Tidak perlu memeriksa isProfilePhotoValid karena jika foto profil diubah, itu sudah valid

            // Periksa apakah ada perubahan data dibandingkan dengan data awal
            const isDataChanged = (
                fullNameInput.value.trim() !== initialFullName ||
                emailInput.value.trim() !== initialEmail ||
                registrationNumberInput.value.trim() !== initialRegistrationNumber ||
                croppedImageInput.value.trim() !== initialProfilePhoto
            );

            // Mengaktifkan tombol jika semua validasi terpenuhi dan ada perubahan data
            saveChangesButton.disabled = !(isFullNameValid && isEmailValid && isRegistrationNumberValid && isDataChanged);
        }

        // Event listener tambahan untuk validasi form kata sandi
        document.addEventListener("DOMContentLoaded", function() {
            const oldPasswordInput = document.getElementById('oldPassword');
            const newPasswordInput = document.getElementById('newPassword');
            const repeatPasswordInput = document.getElementById('repeatPassword');
            const passwordSecurity = document.querySelector('.password-security');
            const updatePasswordButton = document.getElementById('updatePasswordButton');
            let typingTimeout;

            /**
             * Fungsi untuk memvalidasi Kata Sandi Lama
             * Tidak menampilkan simbol (✔) atau (✘) pada kata sandi lama
             * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
             */
            function validateOldPassword() {
                const oldPassword = oldPasswordInput.value.trim();
                const isValid = oldPassword.length > 0 && oldPassword.length <= 255;
                // Tidak memanggil updateValidityIcon untuk kata sandi lama
                return isValid;
            }

            /**
             * Fungsi untuk memvalidasi Kata Sandi Baru
             * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
             */
            function validateNewPassword() {
                const password = newPasswordInput.value;
                const minLength = password.length >= 8;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
                const maxLength = password.length <= 255;

                // Memperbarui tampilan keamanan kata sandi berdasarkan validasi
                document.querySelector('.terdiri-dari-minimal').style.fontWeight = minLength ? 'normal' : 'bold';
                document.querySelector('.icon').src = minLength ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

                document.querySelector('.terdiri-dari-minimal1').style.fontWeight = hasUpperCase ? 'normal' : 'bold';
                document.querySelector('.icon1').src = hasUpperCase ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

                document.querySelector('.terdiri-dari-minimal2').style.fontWeight = hasSpecialChar ? 'normal' : 'bold';
                document.querySelector('.icon2').src = hasSpecialChar ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

                const isValid = minLength && hasUpperCase && hasSpecialChar && maxLength;

                // Hanya tampilkan simbol jika ada input
                if (password.trim() !== '') {
                    updateValidityIcon(newPasswordInput, '.password-validity', isValid);
                } else {
                    // Hapus simbol jika input kosong
                    const element = document.querySelector('.password-validity');
                    if (element) {
                        element.textContent = '';
                        element.style.color = '';
                    }
                }

                return isValid;
            }

            /**
             * Fungsi untuk memvalidasi Ulangi Kata Sandi Baru
             * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
             */
            function validateRepeatPassword() {
                const newPassword = newPasswordInput.value;
                const repeatPassword = repeatPasswordInput.value;
                const isValid = newPassword === repeatPassword && repeatPassword.length > 0 && repeatPassword.length <= 255;

                // Hanya tampilkan simbol jika ada input
                if (repeatPassword.trim() !== '') {
                    updateValidityIcon(repeatPasswordInput, '.password-match-validity', isValid);
                } else {
                    // Hapus simbol jika input kosong
                    const element = document.querySelector('.password-match-validity');
                    if (element) {
                        element.textContent = '';
                        element.style.color = '';
                    }
                }

                return isValid;
            }

            /**
             * Fungsi untuk memvalidasi keseluruhan form kata sandi
             */
            function validatePasswordForm() {
                const isOldPasswordValid = validateOldPassword();
                const isNewPasswordValid = validateNewPassword();
                const isRepeatPasswordValid = validateRepeatPassword();

                const isFormValid = isOldPasswordValid && isNewPasswordValid && isRepeatPasswordValid;
                updatePasswordButton.disabled = !isFormValid;
            }

            // Menambahkan event listeners untuk validasi hanya pada event 'input'
            oldPasswordInput.addEventListener('input', validatePasswordForm);
            newPasswordInput.addEventListener('input', function() {
                clearTimeout(typingTimeout);
                passwordSecurity.style.display = 'block';
                validatePasswordForm();

                // Menyembunyikan informasi keamanan setelah 500ms jika input kosong
                typingTimeout = setTimeout(function() {
                    if (newPasswordInput.value === '') {
                        passwordSecurity.style.display = 'none';
                    }
                }, 500); // Delay 500ms sebelum menyembunyikan div keamanan
            });

            // Menampilkan informasi keamanan saat input password mendapatkan fokus
            newPasswordInput.addEventListener('focus', function() {
                clearTimeout(typingTimeout);
                passwordSecurity.style.display = 'block';
            });

            // Menyembunyikan informasi keamanan saat input password kehilangan fokus
            newPasswordInput.addEventListener('blur', function() {
                typingTimeout = setTimeout(function() {
                    passwordSecurity.style.display = 'none';
                }, 0); // Delay 0ms sebelum menyembunyikan div keamanan
            });

            repeatPasswordInput.addEventListener('input', validatePasswordForm);
        });

        /**
         * Fungsi untuk memperbarui ikon validitas untuk Nomor Induk
         * @param {HTMLElement} inputElement - Elemen input yang divalidasi
         * @param {string} selector - Selector untuk elemen yang menampilkan ikon
         * @param {boolean} isValid - Status validasi
         */
        function updateRegistrationValidityIcon(inputElement, selector, isValid) {
            const element = document.querySelector(selector);
            if (!element) return; // Pastikan elemen ada

            if (isValid) {
                element.textContent = '(✔)';
                element.style.color = 'green';
            } else {
                element.textContent = '(✘)';
                element.style.color = 'red';
            }
        }

        /**
         * Fungsi untuk memvalidasi input Nomor Induk secara terpisah
         * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
         */
        function validateRegistrationNumberInput() {
            const registrationNumberInput = document.getElementById('registration_number');
            const number = registrationNumberInput.value.trim();
            const isValid = /^\d+$/.test(number) && number.length > 0 && number.length <= 255;
            updateRegistrationValidityIcon(registrationNumberInput, '.registration-validity', isValid);
            return isValid;
        }

        // Event listener tambahan untuk validasi input Nomor Induk
        document.addEventListener("DOMContentLoaded", function() {
            const registrationNumberInput = document.getElementById('registration_number');

            registrationNumberInput.addEventListener('input', function() {
                validateRegistrationNumberInput();
                // Trigger validation form setelah mengubah input
                validateForm();
            });
        });

        // Event listener tambahan untuk validasi form dan tombol saat foto profil diupload
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById('fileInput');
            const groupChild = document.querySelector('.group-child');
            const uploadForm = document.getElementById('uploadForm');
            const croppedImageInput = document.getElementById('croppedImage');
            const saveChangesButton = document.getElementById('saveChangesButton');
            let hasProfilePhoto = <?php echo !empty($user->src_profile_photo) ? 'true' : 'false'; ?>;
            // initialProfilePhoto sudah disimpan di awal
            const progressOverlay = document.getElementById('progressOverlay');

            /**
             * Event listener ketika file diupload dan validasi berhasil
             */
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    if ((file.type === 'image/png' || file.type === 'image/jpeg') && file.size <= 2000000) {
                        // Reset posisi overlay sebelum animasi
                        progressOverlay.classList.remove('active');
                        setTimeout(() => {
                            progressOverlay.classList.add('active'); // Mulai animasi
                        }, 100); // Delay kecil untuk memastikan transisi

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = new Image();
                            img.onload = function() {
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d');

                                // Proses cropping dan scaling gambar
                                const size = Math.min(img.width, img.height);
                                const startX = (img.width - size) / 2;
                                const startY = (img.height - size) / 2;

                                canvas.width = 500;
                                canvas.height = 500;

                                ctx.drawImage(img, startX, startY, size, size, 0, 0, 500, 500);

                                const dataURL = canvas.toDataURL('image/png');

                                // Simulasi proses upload (2 detik)
                                setTimeout(() => {
                                    croppedImageInput.value = dataURL;
                                    groupChild.src = dataURL;
                                    isProfilePhotoUploaded = true;

                                    // Reset animasi overlay setelah upload selesai
                                    progressOverlay.classList.remove('active');

                                    // Panggil validateForm() untuk memvalidasi dan memperbarui status form
                                    validateForm();
                                }, 2000); // Sesuaikan dengan durasi animasi
                            };
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Menampilkan pesan error jika format atau ukuran file tidak sesuai
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Silakan upload gambar PNG atau JPG dengan ukuran kurang dari 2MB.'
                        });
                        fileInput.value = ''; // Kosongkan input file
                    }
                }
            });

            /**
             * Event listener ketika form di-submit menggunakan AJAX
             */
            uploadForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman form secara default

                // Validasi input nama, email, dan nomor induk
                const fullName = document.getElementById('full_name').value.trim();
                const email = document.getElementById('email_address').value.trim();
                const registrationNumber = document.getElementById('registration_number').value.trim();

                if (fullName === '' || email === '' || registrationNumber === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Form Tidak Lengkap',
                        text: 'Nama lengkap, email, dan nomor induk tidak boleh kosong.'
                    });
                    return;
                }

                // Validasi format email
                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailPattern.test(email)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Email Tidak Valid',
                        text: 'Silakan masukkan alamat email yang benar.'
                    });
                    return;
                }

                // Validasi nomor induk
                if (!/^\d+$/.test(registrationNumber)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Nomor Induk Tidak Valid',
                        text: 'Nomor Induk hanya boleh berisi angka.'
                    });
                    return;
                }

                // Validasi apakah ada gambar yang di-upload jika pengguna belum memiliki foto profil
                if (!hasProfilePhoto) {
                    if (croppedImageInput.value === '' && fileInput.files.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Foto Profil Diperlukan',
                            text: 'Silakan upload gambar profil.'
                        });
                        return;
                    }
                }

                // Menyiapkan data form
                const formData = new FormData(uploadForm);

                // Menampilkan SweetAlert2 loading
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Melakukan permintaan AJAX
                $.ajax({
                    url: uploadForm.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Menutup SweetAlert2 loading
                        Swal.close();

                        // Menangani respons dari server
                        try {
                            const data = typeof response === 'string' ? JSON.parse(response) : response;
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#2563EB'
                                }).then(() => {
                                    // Reload halaman atau lakukan tindakan lain sesuai kebutuhan
                                    location.reload();
                                });
                            } else if (data.status === 'error') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: data.message,
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#2563EB'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: 'Respons tidak dikenali dari server.',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#2563EB'
                                });
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Terjadi kesalahan saat memproses respons dari server.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#2563EB'
                            });
                        }
                    },
                    error: function() {
                        // Menutup SweetAlert2 loading
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mengunggah data.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2563EB'
                        });
                    }
                });
            });
        });

        // Event listener tambahan untuk validasi form kata sandi
        document.addEventListener("DOMContentLoaded", function() {
            const oldPasswordInput = document.getElementById('oldPassword');
            const newPasswordInput = document.getElementById('newPassword');
            const repeatPasswordInput = document.getElementById('repeatPassword');
            const passwordSecurity = document.querySelector('.password-security');
            const updatePasswordButton = document.getElementById('updatePasswordButton');
            let typingTimeout;

            /**
             * Fungsi untuk memvalidasi Kata Sandi Lama
             * Tidak menampilkan simbol (✔) atau (✘) pada kata sandi lama
             * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
             */
            function validateOldPassword() {
                const oldPassword = oldPasswordInput.value.trim();
                const isValid = oldPassword.length > 0 && oldPassword.length <= 255;
                // Tidak memanggil updateValidityIcon untuk kata sandi lama
                return isValid;
            }

            /**
             * Fungsi untuk memvalidasi Kata Sandi Baru
             * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
             */
            function validateNewPassword() {
                const password = newPasswordInput.value;
                const minLength = password.length >= 8;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
                const maxLength = password.length <= 255;

                // Memperbarui tampilan keamanan kata sandi berdasarkan validasi
                document.querySelector('.terdiri-dari-minimal').style.fontWeight = minLength ? 'normal' : 'bold';
                document.querySelector('.icon').src = minLength ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

                document.querySelector('.terdiri-dari-minimal1').style.fontWeight = hasUpperCase ? 'normal' : 'bold';
                document.querySelector('.icon1').src = hasUpperCase ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

                document.querySelector('.terdiri-dari-minimal2').style.fontWeight = hasSpecialChar ? 'normal' : 'bold';
                document.querySelector('.icon2').src = hasSpecialChar ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

                const isValid = minLength && hasUpperCase && hasSpecialChar && maxLength;

                // Hanya tampilkan simbol jika ada input
                if (password.trim() !== '') {
                    updateValidityIcon(newPasswordInput, '.password-validity', isValid);
                } else {
                    // Hapus simbol jika input kosong
                    const element = document.querySelector('.password-validity');
                    if (element) {
                        element.textContent = '';
                        element.style.color = '';
                    }
                }

                return isValid;
            }

            /**
             * Fungsi untuk memvalidasi Ulangi Kata Sandi Baru
             * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
             */
            function validateRepeatPassword() {
                const newPassword = newPasswordInput.value;
                const repeatPassword = repeatPasswordInput.value;
                const isValid = newPassword === repeatPassword && repeatPassword.length > 0 && repeatPassword.length <= 255;

                // Hanya tampilkan simbol jika ada input
                if (repeatPassword.trim() !== '') {
                    updateValidityIcon(repeatPasswordInput, '.password-match-validity', isValid);
                } else {
                    // Hapus simbol jika input kosong
                    const element = document.querySelector('.password-match-validity');
                    if (element) {
                        element.textContent = '';
                        element.style.color = '';
                    }
                }

                return isValid;
            }

            /**
             * Fungsi untuk memvalidasi keseluruhan form kata sandi
             */
            function validatePasswordForm() {
                const isOldPasswordValid = validateOldPassword();
                const isNewPasswordValid = validateNewPassword();
                const isRepeatPasswordValid = validateRepeatPassword();

                const isFormValid = isOldPasswordValid && isNewPasswordValid && isRepeatPasswordValid;
                updatePasswordButton.disabled = !isFormValid;
            }

            // Menambahkan event listeners untuk validasi hanya pada event 'input'
            oldPasswordInput.addEventListener('input', validatePasswordForm);
            newPasswordInput.addEventListener('input', function() {
                clearTimeout(typingTimeout);
                passwordSecurity.style.display = 'block';
                validatePasswordForm();

                // Menyembunyikan informasi keamanan setelah 500ms jika input kosong
                typingTimeout = setTimeout(function() {
                    if (newPasswordInput.value === '') {
                        passwordSecurity.style.display = 'none';
                    }
                }, 500); // Delay 500ms sebelum menyembunyikan div keamanan
            });

            // Menampilkan informasi keamanan saat input password mendapatkan fokus
            newPasswordInput.addEventListener('focus', function() {
                clearTimeout(typingTimeout);
                passwordSecurity.style.display = 'block';
            });

            // Menyembunyikan informasi keamanan saat input password kehilangan fokus
            newPasswordInput.addEventListener('blur', function() {
                typingTimeout = setTimeout(function() {
                    passwordSecurity.style.display = 'none';
                }, 0); // Delay 0ms sebelum menyembunyikan div keamanan
            });

            repeatPasswordInput.addEventListener('input', validatePasswordForm);
        });

        /**
         * Fungsi untuk memperbarui tanggal dan waktu yang ditampilkan
         */
        function updateDateTime() {
            const now = new Date();
            const optionsDate = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const optionsTime = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };

            // Format tanggal dan jam dalam bahasa Indonesia
            const dateString = now.toLocaleDateString('id-ID', optionsDate);
            const timeString = now.toLocaleTimeString('id-ID', optionsTime);

            // Mengupdate elemen HTML dengan tanggal dan waktu saat ini
            document.getElementById('dateDisplay').innerText = dateString;
            document.getElementById('timeDisplay').innerText = timeString;
        }

        // Memanggil fungsi updateDateTime secara terus-menerus tanpa jeda
        setInterval(updateDateTime, 0);

        // Memastikan waktu saat ini ditampilkan saat memuat halaman
        updateDateTime();

        /**
         * Menambahkan fungsionalitas fokus otomatis pada input saat klik pada input-field-inner
         */
        document.addEventListener("DOMContentLoaded", function() {
            const inputFieldInners = document.querySelectorAll('.input-field-inner');

            inputFieldInners.forEach(function(wrapper) {
                wrapper.addEventListener('click', function(e) {
                    // Prevent triggering if the click is inside the input
                    if (e.target.tagName.toLowerCase() === 'input') {
                        return;
                    }

                    const input = wrapper.querySelector('input');
                    if (input) {
                        input.focus();
                        if (input.value) {
                            // Gunakan setTimeout untuk memastikan setSelectionRange dipanggil setelah fokus diterapkan
                            setTimeout(function() {
                                const len = input.value.length;
                                input.setSelectionRange(len, len);
                            }, 0);
                        }
                    }
                });
            });
        });

        // Menambahkan event listener untuk animasi ikon kamera
        document.addEventListener("DOMContentLoaded", function() {
            const cameraIcon = document.querySelector('.iconsolidcamera');
            const iconCamera = cameraIcon.querySelector('.icon-camera');
            const iconUpload = cameraIcon.querySelector('.icon-upload');

            // Menambahkan event listener untuk hover
            cameraIcon.addEventListener('mouseenter', () => {
                cameraIcon.classList.add('hovered');
            });

            cameraIcon.addEventListener('mouseleave', () => {
                cameraIcon.classList.remove('hovered');
            });
        });

        // Menambahkan event listener untuk animasi ikon kamera dan efek pada group-child
        document.addEventListener("DOMContentLoaded", function() {
            const cameraIcons = document.querySelectorAll('.iconsolidcamera');

            cameraIcons.forEach(function(cameraIcon) {
                const groupChild = cameraIcon.parentElement.querySelector('.group-child');

                // Menambahkan event listener untuk hover
                cameraIcon.addEventListener('mouseenter', () => {
                    groupChild.classList.add('rainbow-border');
                });

                cameraIcon.addEventListener('mouseleave', () => {
                    groupChild.classList.remove('rainbow-border');
                });
            });
        });

        /**
         * Fungsi yang dijalankan saat seluruh halaman telah dimuat.
         */
        window.onload = function() {
            // Daftar pasangan elemen target dan induknya (kosong karena tidak ada pasangan yang diberikan)
            const targetPairs = [];

            // Daftar kelas atau atribut data yang akan dianimasikan secara langsung
            const directClassesToAnimate = [
                "nama-pengguna",
                "email-pengguna"
            ];

            // Gabungan semua elemen yang perlu dianimasikan
            const elementsToAnimate = [];

            /**
             * Fungsi untuk memeriksa apakah sebuah elemen mengalami overflow.
             * @param {HTMLElement} element - Elemen yang akan diperiksa.
             * @returns {Object} Informasi tentang apakah elemen mengalami overflow dan detail lainnya.
             */
            function isElementOverflowing(element) {
                const scrollWidth = element.scrollWidth;
                const clientWidth = element.clientWidth;
                const difference = scrollWidth - clientWidth;
                const overflowing = scrollWidth > clientWidth + 1; // Toleransi 1px

                return {
                    overflowing,
                    scrollWidth,
                    clientWidth,
                    difference
                };
            }

            /**
             * Fungsi untuk menyiapkan animasi scroll pada elemen tertentu.
             * @param {HTMLElement} element - Elemen yang akan dianimasikan.
             */
            function setupScrollAnimation(element) {
                const overflowInfo = isElementOverflowing(element);

                if (!overflowInfo.overflowing) {
                    console.log(`Tidak perlu animasi untuk elemen:`, element);
                    console.log(`Alasan: scrollWidth (${overflowInfo.scrollWidth}px) - clientWidth (${overflowInfo.clientWidth}px) = ${overflowInfo.difference}px (Tidak melebihi toleransi 1px)`);
                    return;
                } else {
                    console.log(`Animasi diperlukan untuk elemen:`, element);
                    console.log(`Alasan: scrollWidth (${overflowInfo.scrollWidth}px) - clientWidth (${overflowInfo.clientWidth}px) = ${overflowInfo.difference}px (Melebihi toleransi 1px)`);
                }

                // Menyimpan teks asli dalam atribut data untuk pemulihan nanti
                const originalText = element.textContent.trim();
                element.setAttribute('data-original-text', originalText);

                // Menerapkan gaya default dengan ellipsis
                element.style.overflow = "hidden";
                element.style.textOverflow = "ellipsis";
                element.style.whiteSpace = "nowrap";

                /**
                 * Fungsi untuk memulai animasi scroll.
                 */
                function startScroll() {
                    if (element.getAttribute('data-animating') === 'true') return;

                    element.setAttribute('data-animating', 'true');

                    // Menghilangkan text-overflow: ellipsis saat animasi berjalan
                    element.style.textOverflow = "unset";
                    element.style.whiteSpace = "nowrap";
                    element.style.overflow = "hidden";

                    // Mengganti innerHTML dengan scroll-container dan scroll-text
                    element.innerHTML = `
                    <div class="scroll-container">
                        <span class="scroll-text">${originalText}&nbsp;&nbsp;&nbsp;</span>
                        <span class="scroll-text">${originalText}&nbsp;&nbsp;&nbsp;</span>
                    </div>
                `;

                    const scrollContainer = element.querySelector(".scroll-container");
                    const scrollTexts = element.querySelectorAll(".scroll-text");

                    // Menghitung lebar total teks
                    const textWidth = scrollTexts[0].offsetWidth;
                    const totalWidth = textWidth * 2;

                    // Mengatur lebar scroll-container agar cukup untuk menampung teks
                    scrollContainer.style.width = `${totalWidth}px`;
                    scrollContainer.style.display = "flex";

                    // Variabel animasi
                    let position = 0; // Posisi awal scroll
                    const speed = 30; // Kecepatan animasi dalam piksel per detik

                    let lastTimestamp = null;
                    let animationId = null; // ID untuk requestAnimationFrame

                    /**
                     * Fungsi animasi menggunakan requestAnimationFrame.
                     * @param {DOMHighResTimeStamp} timestamp - Waktu saat frame animasi.
                     */
                    function animate(timestamp) {
                        if (!lastTimestamp) lastTimestamp = timestamp;
                        const delta = timestamp - lastTimestamp;
                        lastTimestamp = timestamp;

                        // Menghitung pergeseran berdasarkan kecepatan
                        position -= (speed * delta) / 1000; // Konversi delta ke detik

                        if (Math.abs(position) >= textWidth) {
                            // Reset posisi untuk loop infinite
                            position += textWidth;
                        }

                        scrollContainer.style.transform = `translateX(${position}px)`;

                        // Melanjutkan animasi frame berikutnya
                        animationId = requestAnimationFrame(animate);
                    }

                    // Mulai animasi scroll
                    animationId = requestAnimationFrame(animate);

                    // Menyimpan ID animasi dalam atribut data untuk referensi nanti
                    element.setAttribute('data-animation-id', animationId);
                }

                /**
                 * Fungsi untuk menghentikan animasi scroll dan mengembalikan posisi awal.
                 */
                function stopScroll() {
                    if (element.getAttribute('data-animating') !== 'true') return;

                    const animationId = element.getAttribute('data-animation-id');
                    if (animationId) {
                        cancelAnimationFrame(animationId);
                    }

                    // Mengembalikan innerHTML ke teks asli
                    const originalText = element.getAttribute('data-original-text');
                    element.innerHTML = originalText;

                    // Menerapkan kembali text-overflow: ellipsis
                    element.style.textOverflow = "ellipsis";
                    element.style.whiteSpace = "nowrap";
                    element.style.overflow = "hidden";

                    // Menghapus tanda bahwa elemen sedang dalam keadaan animasi
                    element.setAttribute('data-animating', 'false');
                }

                // Menyimpan fungsi startScroll dan stopScroll ke elemen untuk akses mudah
                element.startScroll = startScroll;
                element.stopScroll = stopScroll;
            }

            // Mengolah pasangan target dengan induknya (tidak ada pasangan yang diberikan)
            targetPairs.forEach(pair => {
                const {
                    targetClass,
                    parentClass
                } = pair;
                const parentElements = document.querySelectorAll(`.${parentClass}`);

                parentElements.forEach(parent => {
                    const targetElements = parent.querySelectorAll(`.${targetClass}`);
                    if (targetElements.length === 0) {
                        console.warn(`Tidak menemukan elemen target dengan kelas "${targetClass}" dalam induk:`, parent);
                        return;
                    }

                    targetElements.forEach(target => {
                        setupScrollAnimation(target);
                        elementsToAnimate.push({
                            element: target,
                            type: 'parent'
                        });
                    });

                    // Menambahkan event listener pada induk untuk hover
                    parent.addEventListener("mouseenter", function() {
                        targetElements.forEach(target => {
                            if (isElementOverflowing(target).overflowing) {
                                target.startScroll();
                            }
                        });
                    });

                    parent.addEventListener("mouseleave", function() {
                        targetElements.forEach(target => {
                            target.stopScroll();
                        });
                    });
                });
            });

            // Mengolah elemen-elemen yang dianimasikan secara langsung berdasarkan kelas
            directClassesToAnimate.forEach(className => {
                // Jika ini adalah kelas, gunakan selector kelas
                const elements = document.querySelectorAll(`.${className}`);

                elements.forEach(element => {
                    setupScrollAnimation(element);
                    elementsToAnimate.push({
                        element: element,
                        type: 'direct'
                    });

                    const overflowInfo = isElementOverflowing(element);
                    if (overflowInfo.overflowing) {
                        // Menambahkan event listener untuk memulai dan menghentikan animasi saat hover
                        element.addEventListener("mouseenter", function() {
                            element.startScroll();
                        });

                        element.addEventListener("mouseleave", function() {
                            element.stopScroll();
                        });
                    }
                });
            });

            /**
             * Event listener untuk menangani resize window.
             * Menyesuaikan animasi pada elemen yang sudah ada.
             */
            window.addEventListener('resize', function() {
                elementsToAnimate.forEach(item => {
                    const {
                        element
                    } = item;

                    // Batalkan animasi jika sedang berjalan
                    if (element.getAttribute('data-animating') === 'true') {
                        const animationId = element.getAttribute('data-animation-id');
                        if (animationId) {
                            cancelAnimationFrame(animationId);
                        }
                        const originalText = element.getAttribute('data-original-text');
                        element.innerHTML = originalText;
                        element.style.textOverflow = "ellipsis";
                        element.style.whiteSpace = "nowrap";
                        element.style.overflow = "hidden";
                        element.setAttribute('data-animating', 'false');
                    }

                    // Periksa kembali overflow dan terapkan animasi jika perlu
                    setupScrollAnimation(element);

                    const overflowInfo = isElementOverflowing(element);
                    if (overflowInfo.overflowing) {
                        if (item.type === 'parent') {
                            // Pastikan event listener pada parent sudah ditetapkan
                            const parent = element.parentElement;
                            if (parent) {
                                parent.addEventListener("mouseenter", function() {
                                    element.startScroll();
                                });

                                parent.addEventListener("mouseleave", function() {
                                    element.stopScroll();
                                });
                            }
                        } else if (item.type === 'direct') {
                            // Pastikan event listener pada elemen langsung
                            element.addEventListener("mouseenter", function() {
                                element.startScroll();
                            });

                            element.addEventListener("mouseleave", function() {
                                element.stopScroll();
                            });
                        }
                    }
                });
            });
        };
    </script>
</body>

</html>