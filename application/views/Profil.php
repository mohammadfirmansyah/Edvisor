<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/profil.css" />
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
                    <img oncontextmenu="return false;" class="profile-photo" src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>" alt="">
                </div>
                <div class="nama-pengguna"><?= htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="email-pengguna"><?= htmlspecialchars($user->email_address, ENT_QUOTES, 'UTF-8'); ?></div>
            </a>
            <!-- Menu navigasi utama -->
            <div class="menu-bar">
                <a class="item-side-bar-default link" href="sidebarBeranda">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_beranda.svg">
                    <div class="text-sidebar-default">Beranda</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarGuruModel">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_guru_model.svg">
                    <div class="text-sidebar-default">Guru Model</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarObserver">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_observer.svg">
                    <div class="text-sidebar-default">Observer</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarBantuan">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_bantuan.svg">
                    <div class="text-sidebar-default">Bantuan</div>
                </a>
            </div>
            <!-- Link untuk keluar dari akun -->
            <a class="item-side-bar-exit link" href="sidebarLogout">
                <img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_keluar.svg">
                <div class="text-sidebar-default">Keluar</div>
            </a>
        </div>
        <!-- Navigasi kembali ke beranda -->
        <a class="profil-saya-parent link" href="sidebarBeranda">
            <img oncontextmenu="return false;" class="iconsolidarrow-left" alt="" src="assets/img/icon_arrow_left.svg">
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
                    <!-- Menampilkan foto profil pengguna, jika ada -->
                    <img oncontextmenu="return false;" class="group-child" alt="Foto Profil" src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>">

                    <!-- Input file untuk mengupload foto profil -->
                    <input type="file" id="fileInput" name="profile_photo" accept="image/png, image/jpeg" style="display: none;" />

                    <!-- Input hidden untuk menyimpan data gambar yang telah di-crop -->
                    <input type="hidden" id="croppedImage" name="croppedImage">

                    <!-- Icon kamera yang dapat diklik untuk membuka dialog upload -->
                    <div class="iconsolidcamera" onclick="document.getElementById('fileInput').click();">
                        <img oncontextmenu="return false;" src="assets/img/icon_camera.svg" alt="camera" class="icon-camera">
                        <img oncontextmenu="return false;" src="assets/img/icon_upload_profile.svg" alt="upload" class="icon-upload" style="display: none;">
                    </div>

                    <!-- Input untuk Nama Lengkap -->
                    <div class="input-field">
                        <div class="label">Nama Lengkap</div>
                        <div class="input-field-inner">
                            <div class="text-filled-wrapper">
                                <input type="text" id="full_name" name="full_name" class="text-filled1" placeholder="Silakan masukkan nama lengkap" value="<?php echo htmlspecialchars(isset($user->full_name) ? $user->full_name : ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Input untuk Email -->
                    <div class="input-field1">
                        <div class="label">Email</div>
                        <div class="input-field-inner">
                            <div class="text-filled-wrapper">
                                <input type="email" id="email_address" name="email_address" class="text-filled1" placeholder="Silakan masukkan alamat E-mail" value="<?php echo htmlspecialchars(isset($user->email_address) ? $user->email_address : ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Input untuk Nomor Induk -->
                    <div class="input-field2">
                        <div class="label">Nomor Induk</div>
                        <div class="input-field-inner">
                            <div class="text-filled-wrapper">
                                <input type="text" id="registration_number" name="registration_number" class="text-filled1" placeholder="Silakan masukkan Nomor Induk" value="<?php echo htmlspecialchars(isset($user->registration_number) ? $user->registration_number : ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tombol untuk menyimpan perubahan -->
                <button type="submit" class="button link">
                    <div class="button1">Simpan Perubahan</div>
                </button>
            </form>
        </div>
        <!-- Form untuk memperbarui kata sandi -->
        <form enctype="multipart/form-data" action="formPerbaruiPasswordPengguna" method="POST">
            <div class="perbaru-kata-sandi-parent">
                <div class="perbaru-kata-sandi">Perbarui Kata Sandi</div>

                <!-- Kata Sandi Lama -->
                <div class="kata-sandi">
                    <div class="label">Kata Sandi Lama <span class="old-password-validity"></span></div>
                    <div class="input-field-inner">
                        <div class="text-filled-wrapper">
                            <input type="password" id="oldPassword" name="current_password" class="text-filled" placeholder="Masukkan Kata Sandi Lama">
                        </div>
                        <!-- Icon untuk toggle visibilitas kata sandi -->
                        <img oncontextmenu="return false;" class="kata-sandi-child" alt="Toggle Password" src="assets/img/icon_eye.svg" id="toggleOldPassword">
                    </div>
                </div>

                <!-- Kata Sandi Baru -->
                <div class="regis-kata-sandi">
                    <div class="label">Kata Sandi Baru <span class="password-validity"></span></div>
                    <div class="input-field-inner input-field-inner1">
                        <div class="text-filled-wrapper">
                            <input type="password" id="newPassword" name="new_password" class="text-filled" placeholder="Masukkan Kata Sandi Baru">
                        </div>
                        <!-- Icon untuk toggle visibilitas kata sandi -->
                        <img oncontextmenu="return false;" class="kata-sandi-child" alt="Toggle Password" src="assets/img/icon_eye.svg" id="toggleNewPassword">
                    </div>
                    <!-- Informasi keamanan kata sandi -->
                    <div class="password-security">
                        <div class="security-warning">
                            <img oncontextmenu="return false;" class="icon" alt="Security Icon" src="assets/img/icon_shield_warning.svg">
                            <div class="terdiri-dari-minimal">Terdiri dari minimal 8 karakter</div>
                        </div>
                        <div class="security-warning1">
                            <img oncontextmenu="return false;" class="icon1" alt="Security Icon" src="assets/img/icon_shield_warning.svg">
                            <div class="terdiri-dari-minimal1">Mengandung setidaknya 1 huruf kapital</div>
                        </div>
                        <div class="security-warning2">
                            <img oncontextmenu="return false;" class="icon2" alt="Security Icon" src="assets/img/icon_shield_warning.svg">
                            <div class="terdiri-dari-minimal2">Mengandung setidaknya 1 karakter khusus (misalnya: !, @, #, $, dll.).</div>
                        </div>
                    </div>
                </div>

                <!-- Ulangi Kata Sandi Baru -->
                <div class="kata-sandi1">
                    <div class="label">Ulangi Kata Sandi Baru <span class="password-match-validity"></span></div>
                    <div class="input-field-inner">
                        <div class="text-filled-wrapper">
                            <input type="password" id="repeatPassword" name="confirm_password" class="text-filled" placeholder="Ulangi Kata Sandi Baru">
                        </div>
                        <!-- Icon untuk toggle visibilitas kata sandi -->
                        <img oncontextmenu="return false;" class="kata-sandi-child" alt="Toggle Password" src="assets/img/icon_eye.svg" id="toggleRepeatPassword">
                    </div>
                </div>

                <!-- Tombol Submit untuk memperbarui kata sandi -->
                <button disabled type="submit" class="button2 link">
                    <div class="button1">Perbarui Kata Sandi</div>
                </button>
            </div>
        </form>
    </div>
</body>

<script>
    // Menunggu hingga seluruh konten DOM dimuat
    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen flashdata
        var flashdata = document.getElementById('flashdata');
        var success = flashdata.getAttribute('data-success');
        var error = flashdata.getAttribute('data-error');

        // Menampilkan SweetAlert untuk pesan sukses
        if (success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: success,
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563EB'
            });
        }

        // Menampilkan SweetAlert untuk pesan error
        if (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error,
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563EB'
            });
        }
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

    // Memanggil fungsi updateDateTime setiap setengah detik
    setInterval(updateDateTime, 500);

    // Memastikan waktu saat ini ditampilkan saat memuat halaman
    updateDateTime();

    // Menambahkan event listener ketika DOM telah dimuat
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById('fileInput');
        const groupChild = document.querySelector('.group-child');
        const uploadForm = document.getElementById('uploadForm');
        const croppedImageInput = document.getElementById('croppedImage');
        const emailInput = document.getElementById('email_address');
        var hasProfilePhoto = <?php echo !empty($user->src_profile_photo) ? 'true' : 'false'; ?>;

        /**
         * Event listener ketika pengguna memilih file gambar
         */
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            // Periksa apakah ada file yang dipilih
            if (file) {
                // Periksa tipe file dan ukuran
                if ((file.type === 'image/png' || file.type === 'image/jpeg') && file.size <= 1000000) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.onload = function() {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            // Tentukan ukuran crop (persegi)
                            const size = Math.min(img.width, img.height);
                            const startX = (img.width - size) / 2;
                            const startY = (img.height - size) / 2;

                            canvas.width = size;
                            canvas.height = size;
                            ctx.drawImage(img, startX, startY, size, size, 0, 0, size, size);

                            // Konversi gambar crop ke PNG
                            const dataURL = canvas.toDataURL('image/png');
                            groupChild.src = dataURL; // Tampilkan gambar yang telah di-crop

                            // Set data URL ke input hidden
                            croppedImageInput.value = dataURL;
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Menampilkan pesan error jika format atau ukuran file tidak sesuai
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Silakan upload gambar PNG atau JPG dengan ukuran kurang dari 1000kB.'
                    });
                    fileInput.value = ''; // Kosongkan input file
                }
            }
        });

        /**
         * Event listener ketika form di-submit
         */
        uploadForm.addEventListener('submit', function(event) {
            // Validasi input nama dan email
            const fullName = document.getElementById('full_name').value.trim();
            const email = document.getElementById('email_address').value.trim();

            if (fullName === '' || email === '') {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Form Tidak Lengkap',
                    text: 'Nama lengkap dan email tidak boleh kosong.'
                });
                return;
            }

            // Validasi apakah ada gambar yang di-upload jika pengguna belum memiliki foto profil
            if (!hasProfilePhoto) {
                if (croppedImageInput.value === '' && fileInput.files.length === 0) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Foto Profil Diperlukan',
                        text: 'Silakan upload gambar profil.'
                    });
                    return;
                }
            }
        });
    });

    // Event listener tambahan untuk validasi input Nomor Induk
    document.addEventListener("DOMContentLoaded", function() {
        const nomorIndukInput = document.getElementById('registration_number');

        /**
         * Fungsi untuk memvalidasi input, hanya mengizinkan angka dan spasi
         * @param {HTMLElement} input - Elemen input yang akan divalidasi
         */
        function validateInput(input) {
            // Menghapus karakter selain angka dan spasi
            input.value = input.value.replace(/[^0-9 ]/g, '');
        }

        // Menambahkan event listener pada input Nomor Induk
        nomorIndukInput.addEventListener('input', function() {
            validateInput(this);
        });
    });

    // Event listener untuk animasi ikon kamera
    document.addEventListener("DOMContentLoaded", function() {
        const cameraIcon = document.querySelector('.iconsolidcamera');
        const iconCamera = cameraIcon.querySelector('.icon-camera');
        const iconUpload = cameraIcon.querySelector('.icon-upload');
        const groupChild = document.querySelector('.group-child');
        let animationInProgress = false;
        let animationQueue = [];

        /**
         * Fungsi untuk menjalankan animasi berikutnya dalam antrean
         */
        function runNextAnimation() {
            if (animationQueue.length > 0) {
                const nextAnimation = animationQueue.shift();
                nextAnimation();
            }
        }

        /**
         * Fungsi untuk melakukan animasi flip ke ikon upload
         */
        function flipToUpload() {
            if (animationInProgress) {
                animationQueue.push(flipToUpload);
                return;
            }
            animationInProgress = true;
            groupChild.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
            iconCamera.classList.add('bounce-up');
            setTimeout(() => {
                iconCamera.style.display = 'none';
                iconCamera.classList.remove('bounce-up');
                iconUpload.style.display = 'block';
                iconUpload.classList.add('bounce-down');
            }, 250);
            setTimeout(() => {
                iconUpload.classList.remove('bounce-down');
                animationInProgress = false;
                runNextAnimation();
            }, 1000);
        }

        /**
         * Fungsi untuk melakukan animasi flip kembali ke ikon kamera
         */
        function flipToCamera() {
            if (animationInProgress) {
                animationQueue.push(flipToCamera);
                return;
            }
            animationInProgress = true;
            groupChild.style.boxShadow = 'none';
            iconUpload.classList.add('bounce-up');
            setTimeout(() => {
                iconUpload.style.display = 'none';
                iconUpload.classList.remove('bounce-up');
                iconCamera.style.display = 'block';
                iconCamera.classList.add('bounce-down');
            }, 500);
            setTimeout(() => {
                iconCamera.classList.remove('bounce-down');
                animationInProgress = false;
                runNextAnimation();
            }, 1000);
        }

        // Menambahkan event listener untuk hover pada ikon kamera
        cameraIcon.addEventListener('mouseenter', () => {
            flipToUpload();
        });

        cameraIcon.addEventListener('mouseleave', () => {
            flipToCamera();
        });

        // Menambahkan event listener untuk klik pada ikon kamera
        cameraIcon.addEventListener('click', () => {
            if (animationInProgress) {
                return;
            }
            animationQueue = [];
            runNextAnimation();
        });
    });

    /**
     * Fungsi untuk toggle visibilitas kata sandi
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

    // Event listener tambahan untuk validasi form kata sandi
    document.addEventListener("DOMContentLoaded", function() {
        const oldPasswordInput = document.getElementById('oldPassword');
        const newPasswordInput = document.getElementById('newPassword');
        const repeatPasswordInput = document.getElementById('repeatPassword');
        const passwordSecurity = document.querySelector('.password-security');
        const updateButton = document.querySelector('.button2');
        let typingTimeout;

        /**
         * Fungsi untuk memvalidasi kata sandi lama
         * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
         */
        function validateOldPassword() {
            return oldPasswordInput.value.trim() !== '';
        }

        /**
         * Fungsi untuk memvalidasi kata sandi baru
         * @param {string} password - Kata sandi yang akan divalidasi
         * @returns {boolean} - Mengembalikan true jika valid, false jika tidak
         */
        function validatePassword(password) {
            const minLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            // Memperbarui tampilan keamanan kata sandi berdasarkan validasi
            document.querySelector('.terdiri-dari-minimal').style.fontWeight = minLength ? 'normal' : 'bold';
            document.querySelector('.icon').src = minLength ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

            document.querySelector('.terdiri-dari-minimal1').style.fontWeight = hasUpperCase ? 'normal' : 'bold';
            document.querySelector('.icon1').src = hasUpperCase ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

            document.querySelector('.terdiri-dari-minimal2').style.fontWeight = hasSpecialChar ? 'normal' : 'bold';
            document.querySelector('.icon2').src = hasSpecialChar ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

            return minLength && hasUpperCase && hasSpecialChar;
        }

        /**
         * Fungsi untuk memvalidasi keseluruhan form
         * @returns {boolean} - Mengembalikan true jika form valid, false jika tidak
         */
        function validateForm() {
            const isOldPasswordValid = validateOldPassword();
            const isPasswordValid = validatePassword(newPasswordInput.value);
            const isPasswordMatch = newPasswordInput.value === repeatPasswordInput.value && repeatPasswordInput.value !== '';

            // Memperbarui ikon validasi
            updateValidityIcon(newPasswordInput, '.password-validity', isPasswordValid);
            updateValidityIcon(repeatPasswordInput, '.password-match-validity', isPasswordMatch);

            const isFormValid = isOldPasswordValid && isPasswordValid && isPasswordMatch;
            updateButton.classList.toggle('disabled', !isFormValid);
            updateButton.disabled = !isFormValid;
            return isFormValid;
        }

        /**
         * Fungsi untuk memperbarui ikon validasi
         * @param {HTMLElement} inputElement - Elemen input yang divalidasi
         * @param {string} selector - Selector untuk elemen yang menampilkan ikon
         * @param {boolean} isValid - Status validasi
         */
        function updateValidityIcon(inputElement, selector, isValid) {
            const element = document.querySelector(selector);
            if (inputElement.value.trim() === '') {
                element.textContent = '';
            } else if (isValid) {
                element.textContent = '(✔)';
                element.style.color = 'green';
            } else {
                element.textContent = '(✘)';
                element.style.color = 'red';
            }
        }

        // Menambahkan event listener pada input kata sandi lama
        oldPasswordInput.addEventListener('input', validateForm);
        // Menambahkan event listener pada input kata sandi baru dengan debounce
        newPasswordInput.addEventListener('input', function() {
            clearTimeout(typingTimeout);
            passwordSecurity.style.display = 'block';
            validateForm();

            typingTimeout = setTimeout(function() {
                if (newPasswordInput.value === '') {
                    passwordSecurity.style.display = 'none';
                }
            }, 500); // Delay 500ms sebelum menyembunyikan div keamanan
        });

        // Menambahkan event listener pada fokus dan blur input kata sandi baru
        newPasswordInput.addEventListener('focus', function() {
            clearTimeout(typingTimeout);
            passwordSecurity.style.display = 'block';
        });

        newPasswordInput.addEventListener('blur', function() {
            typingTimeout = setTimeout(function() {
                passwordSecurity.style.display = 'none';
            }, 0); // Delay 0ms sebelum menyembunyikan div keamanan
        });

        // Menambahkan event listener pada input ulangi kata sandi
        repeatPasswordInput.addEventListener('input', validateForm);
        repeatPasswordInput.addEventListener('focus', validateForm);

        // Validasi awal saat halaman dimuat
        validateForm();
    });

    /**
     * Fungsi untuk menginisialisasi animasi scroll pada elemen tertentu
     */
    window.onload = function() {
        // Daftar pasangan elemen target dan induknya
        const targetPairs = [];

        // Daftar kelas atau atribut data yang akan dianimasikan secara langsung
        const directClassesToAnimate = [
            "nama-pengguna",
            "email-pengguna"
        ];

        // Gabungan semua elemen yang perlu dianimasikan
        const elementsToAnimate = [];

        /**
         * Fungsi untuk memeriksa apakah elemen benar-benar overflow
         * @param {HTMLElement} element - Elemen yang akan diperiksa
         * @returns {object} - Informasi tentang overflow
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
         * Fungsi untuk menangani animasi scroll pada elemen tertentu
         * @param {HTMLElement} element - Elemen yang akan dianimasikan
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

            // Simpan teks asli dalam data attribute untuk pemulihan nanti
            const originalText = element.textContent.trim();
            element.setAttribute('data-original-text', originalText);

            // Terapkan gaya default dengan ellipsis
            element.style.overflow = "hidden";
            element.style.textOverflow = "ellipsis";
            element.style.whiteSpace = "nowrap";

            /**
             * Fungsi untuk memulai animasi scroll
             */
            function startScroll() {
                if (element.getAttribute('data-animating') === 'true') return;

                element.setAttribute('data-animating', 'true');

                // Hilangkan text-overflow: ellipsis saat animasi berjalan
                element.style.textOverflow = "unset";
                element.style.whiteSpace = "nowrap";
                element.style.overflow = "hidden";

                // Ganti innerHTML dengan scroll-container dan scroll-text
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
                let animationId = null; // ID animasi

                /**
                 * Fungsi animasi menggunakan requestAnimationFrame
                 * @param {number} timestamp - Waktu saat ini
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

                // Simpan ID animasi dalam data attribute untuk referensi nanti
                element.setAttribute('data-animation-id', animationId);
            }

            /**
             * Fungsi untuk menghentikan animasi scroll dan mengembalikan posisi awal
             */
            function stopScroll() {
                if (element.getAttribute('data-animating') !== 'true') return;

                const animationId = element.getAttribute('data-animation-id');
                if (animationId) {
                    cancelAnimationFrame(animationId);
                }

                // Kembalikan innerHTML ke teks asli
                const originalText = element.getAttribute('data-original-text');
                element.innerHTML = originalText;

                // Terapkan kembali text-overflow: ellipsis
                element.style.textOverflow = "ellipsis";
                element.style.whiteSpace = "nowrap";
                element.style.overflow = "hidden";

                // Hapus tanda bahwa elemen sedang dalam keadaan animasi
                element.setAttribute('data-animating', 'false');
            }

            // Simpan fungsi startScroll dan stopScroll ke elemen untuk akses mudah
            element.startScroll = startScroll;
            element.stopScroll = stopScroll;
        }

        // Mengolah pasangan target dengan induknya
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

                // Tambahkan event listener pada induk untuk hover
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
                    // Menambahkan event listener untuk hover pada elemen
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
         * Menambahkan event listener untuk resize (opsional)
         * @description Menghentikan animasi saat resize dan memeriksa kembali overflow
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

</html>