<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/signup.css" />
</head>

<body>
    <!-- Flashdata Messages sebagai Data Attributes -->
    <?php
    // Mendapatkan pesan flashdata sukses dan error dari session
    $success_message = $this->session->flashdata('success');
    $error_message = $this->session->flashdata('error');
    ?>
    <!-- Menyimpan pesan flashdata dalam elemen tersembunyi dengan atribut data -->
    <div id="flashdata"
        data-success="<?= isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-error="<?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <!-- Container utama untuk halaman signup -->
    <div class="sign-up unselectable-text">
        <div class="group-parent">
            <!-- Judul sinkronisasi aplikasi mobile dan web -->
            <div class="sinkronisasi-aplikasi-mobile">Sinkronisasi Aplikasi Mobile dan Web</div>
            <!-- Gambar ilustrasi sinkronisasi dengan atribut untuk mencegah klik kanan -->
            <img oncontextmenu="return false;" class="illustrasi-sinkronisasi-icon" alt="" src="assets/img/illustrasi_sinkronisasi.svg">
            <div class="psa1ttmlqiu-1-parent">
                <!-- Informasi ketersediaan aplikasi di platform lain -->
                <div class="tersedia-juga-di">Tersedia juga di:</div>
                <!-- Icon Play Store dengan ID untuk penanganan event klik -->
                <img oncontextmenu="return false;" class="psa1ttmlqiu-1-icon" alt="" src="assets/img/icon_play_store.svg" id="psA1TtmLqiU1Image">
            </div>
        </div>
        <div class="login">
            <div class="logo-ls-1-parent">
                <!-- Logo Lesson Study dengan atribut untuk mencegah klik kanan -->
                <img oncontextmenu="return false;" class="logo-ls-1" alt="" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </div>
            <div class="heading-daftar-akun">
                <!-- Judul dan subjudul form daftar akun -->
                <div class="daftar-akun">Daftar Akun</div>
                <div class="isi-data-berikut">Isi data berikut dengan benar</div>
            </div>
            <!-- Formulir pendaftaran akun dengan metode POST dan enkripsi multipart untuk pengunggahan file -->
            <form enctype="multipart/form-data" action="formSignup" method="POST">
                <!-- Field input untuk Nama Lengkap -->
                <div class="input-field">
                    <div class="label">Nama Lengkap <span class="nama-validity"></span></div>
                    <div class="input-field-inner">
                        <div class="text-filled-wrapper">
                            <!-- Input teks untuk nama lengkap dengan validasi required -->
                            <input type="text" id="nama" name="full_name" class="text-filled1" placeholder="Silakan masukkan nama lengkap" required>
                        </div>
                    </div>
                </div>
                <!-- Field input untuk Alamat E-mail -->
                <div class="input-field1">
                    <div class="label">Alamat E-mail <span class="email-validity"></span></div>
                    <div class="input-field-inner">
                        <div class="text-filled-wrapper">
                            <!-- Input email dengan validasi required -->
                            <input type="email" id="email" name="email_address" class="text-filled1" placeholder="Silakan masukkan alamat E-mail" required>
                        </div>
                    </div>
                </div>
                <!-- Field input untuk Kata Sandi -->
                <div class="regis-kata-sandi">
                    <div class="label">Kata Sandi <span class="password-validity"></span></div>
                    <div class="kata-sandi-inner kata-sandi-inner1">
                        <div class="text-filled-wrapper">
                            <!-- Input password dengan validasi required -->
                            <input type="password" id="password" name="password" class="text-filled" placeholder="Masukkan Kata Sandi" required>
                        </div>
                        <!-- Icon untuk toggle visibilitas password dengan event onclick -->
                        <img oncontextmenu="return false;" class="kata-sandi-child" alt="" src="assets/img/icon_eye.svg" onclick="togglePassword()">
                    </div>
                    <!-- Informasi keamanan kata sandi -->
                    <div class="password-security">
                        <div class="security-warning">
                            <!-- Icon peringatan dan deskripsi aturan minimal karakter -->
                            <img oncontextmenu="return false;" class="icon" alt="" src="assets/img/icon_shield_warning.svg">
                            <div class="terdiri-dari-minimal">Terdiri dari minimal 8 karakter</div>
                        </div>
                        <div class="security-warning1">
                            <!-- Icon peringatan dan deskripsi aturan huruf kapital -->
                            <img oncontextmenu="return false;" class="icon1" alt="" src="assets/img/icon_shield_warning.svg">
                            <div class="mengandung-setidaknya-1">Mengandung setidaknya 1 huruf kapital</div>
                        </div>
                        <div class="security-warning2">
                            <!-- Icon peringatan dan deskripsi aturan karakter khusus -->
                            <img oncontextmenu="return false;" class="icon2" alt="" src="assets/img/icon_shield_warning.svg">
                            <div class="mengandung-setidaknya-11">Mengandung setidaknya 1 karakter khusus (misalnya: !, @, #, $, dll.).</div>
                        </div>
                    </div>
                </div>
                <!-- Field input untuk Ulangi Kata Sandi -->
                <div class="kata-sandi">
                    <div class="label">Ulangi Kata Sandi <span class="password1-validity"></span></div>
                    <div class="kata-sandi-inner">
                        <div class="text-filled-wrapper">
                            <!-- Input password konfirmasi dengan validasi required -->
                            <input type="password" id="password1" name="password_confirm" class="text-filled" placeholder="Ulangi Kata Sandi" required>
                        </div>
                        <!-- Icon untuk toggle visibilitas password konfirmasi -->
                        <img oncontextmenu="return false;" class="kata-sandi-child1" alt="" src="assets/img/icon_eye.svg" onclick="togglePassword()">
                    </div>
                </div>
                <!-- Tombol submit untuk mendaftar -->
                <button type="submit" class="button link" id="buttonContainer">
                    <div class="ini-adalah-text">Daftar</div>
                </button>
            </form>
            <!-- Link untuk masuk jika sudah memiliki akun -->
            <div class="sudah-punya-akun">Sudah punya akun?</div>
            <a class="text-button link" href="pageLogin">
                <div class="ini-adalah-text link-cursor-hovering">Masuk Disini</div>
            </a>
        </div>
    </div>
</body>

<script>
    // Menampilkan pesan flashdata (sukses atau error) menggunakan SweetAlert2 setelah halaman dimuat
    document.addEventListener("DOMContentLoaded", function () {
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
                title: 'Error', // Judul alert
                text: error, // Isi pesan error
                confirmButtonText: 'OK', // Teks tombol konfirmasi
                confirmButtonColor: '#2563EB' // Warna tombol konfirmasi
            });
        }
    });

    // Mendapatkan elemen gambar Play Store berdasarkan ID
    var psA1TtmLqiU1Image = document.getElementById("psA1TtmLqiU1Image");
    if (psA1TtmLqiU1Image) {
        // Menambahkan event listener klik untuk membuka link Play Store
        psA1TtmLqiU1Image.addEventListener("click", function () {
            window.open("https://play.google.com/store/apps/details?id=feri.com.lesson");
        });
    }

    /**
     * Fungsi untuk toggle visibilitas password
     * @param {string} id - ID dari input password
     * @param {string} iconClass - Selector untuk ikon toggle
     */
    function togglePassword(id, iconClass) {
        var passwordInput = document.getElementById(id); // Mendapatkan input password berdasarkan ID
        var toggleIcon = document.querySelector(iconClass); // Mendapatkan ikon toggle berdasarkan selector

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text'; // Mengubah tipe input menjadi teks
            toggleIcon.src = 'assets/img/icon_eye_off.svg'; // Mengubah ikon menjadi eye_off
        } else {
            passwordInput.type = 'password'; // Mengubah tipe input kembali ke password
            toggleIcon.src = 'assets/img/icon_eye.svg'; // Mengubah ikon kembali ke eye
        }
    }

    // Memanggil fungsi togglePassword untuk field password saat ikon diklik
    document.querySelector('.kata-sandi-child').addEventListener('click', function () {
        togglePassword('password', '.kata-sandi-child');
    });

    // Memanggil fungsi togglePassword untuk field konfirmasi password saat ikon diklik
    document.querySelector('.kata-sandi-child1').addEventListener('click', function () {
        togglePassword('password1', '.kata-sandi-child1');
    });

    // Menambahkan event listener setelah DOM selesai dimuat
    document.addEventListener("DOMContentLoaded", function () {
        // Mendapatkan elemen input dan tombol
        var namaInput = document.getElementById('nama');
        var emailInput = document.getElementById('email');
        var passwordInput = document.getElementById('password');
        var password1Input = document.getElementById('password1');
        var daftarButton = document.getElementById('buttonContainer');
        var passwordSecurity = document.querySelector('.password-security');
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
         * Fungsi untuk memvalidasi kekuatan kata sandi
         * @param {string} password - Kata sandi yang akan divalidasi
         * @returns {boolean} - Mengembalikan true jika kata sandi memenuhi semua kriteria, false jika tidak
         */
        function validatePassword(password) {
            var minLength = password.length >= 8; // Memeriksa minimal 8 karakter
            var hasUpperCase = /[A-Z]/.test(password); // Memeriksa setidaknya 1 huruf kapital
            var hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password); // Memeriksa setidaknya 1 karakter khusus

            // Mengubah gaya tampilan berdasarkan validasi minimal karakter
            document.querySelector('.terdiri-dari-minimal').style.fontWeight = minLength ? 'normal' : 'bold';
            document.querySelector('.icon').src = minLength ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

            // Mengubah gaya tampilan berdasarkan validasi huruf kapital
            document.querySelector('.mengandung-setidaknya-1').style.fontWeight = hasUpperCase ? 'normal' : 'bold';
            document.querySelector('.icon1').src = hasUpperCase ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

            // Mengubah gaya tampilan berdasarkan validasi karakter khusus
            document.querySelector('.mengandung-setidaknya-11').style.fontWeight = hasSpecialChar ? 'normal' : 'bold';
            document.querySelector('.icon2').src = hasSpecialChar ? 'assets/img/icon_shield_check.svg' : 'assets/img/icon_shield_warning.svg';

            return minLength && hasUpperCase && hasSpecialChar; // Mengembalikan true jika semua kriteria terpenuhi
        }

        /**
         * Fungsi untuk memvalidasi seluruh form
         */
        function validateForm() {
            var isNameValid = namaInput.value.trim() !== ''; // Memeriksa apakah nama tidak kosong
            var isEmailValid = validateEmail(emailInput.value); // Memeriksa validitas email
            var isPasswordValid = validatePassword(passwordInput.value); // Memeriksa validitas kata sandi
            var isPasswordMatch = passwordInput.value === password1Input.value && password1Input.value !== ''; // Memeriksa kecocokan kata sandi
            var isFormComplete = isNameValid && isEmailValid && isPasswordValid && isPasswordMatch; // Memeriksa apakah semua validasi terpenuhi

            // Memperbarui ikon validitas untuk setiap field
            updateValidityIcon(namaInput, '.nama-validity', isNameValid);
            updateValidityIcon(emailInput, '.email-validity', isEmailValid);
            updateValidityIcon(passwordInput, '.password-validity', isPasswordValid);
            updateValidityIcon(password1Input, '.password1-validity', isPasswordMatch);

            // Mengaktifkan atau menonaktifkan tombol daftar berdasarkan validasi form
            daftarButton.disabled = !isFormComplete;
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

        // Menambahkan event listener pada input password untuk validasi saat mengetik
        passwordInput.addEventListener('input', function () {
            clearTimeout(typingTimeout); // Membersihkan timeout sebelumnya
            passwordSecurity.style.display = 'block'; // Menampilkan informasi keamanan password
            validateForm(); // Memvalidasi form

            // Menyembunyikan informasi keamanan setelah 500ms jika input kosong
            typingTimeout = setTimeout(function () {
                if (passwordInput.value === '') {
                    passwordSecurity.style.display = 'none';
                }
            }, 500); // Delay 500ms sebelum menyembunyikan div keamanan
        });

        // Menampilkan informasi keamanan saat input password mendapatkan fokus
        passwordInput.addEventListener('focus', function () {
            clearTimeout(typingTimeout); // Membersihkan timeout sebelumnya
            passwordSecurity.style.display = 'block'; // Menampilkan informasi keamanan password
        });

        // Menyembunyikan informasi keamanan saat input password kehilangan fokus
        passwordInput.addEventListener('blur', function () {
            typingTimeout = setTimeout(function () {
                passwordSecurity.style.display = 'none'; // Menyembunyikan informasi keamanan
            }, 0); // Delay 0ms sebelum menyembunyikan div keamanan
        });

        // Menambahkan event listener untuk validasi form saat input konfirmasi password berubah
        password1Input.addEventListener('input', validateForm);
        password1Input.addEventListener('focus', validateForm);

        // Menambahkan event listener untuk validasi form saat input nama berubah
        namaInput.addEventListener('input', validateForm);
        namaInput.addEventListener('focus', validateForm);

        // Menambahkan event listener untuk validasi form saat input email berubah
        emailInput.addEventListener('input', validateForm);
        emailInput.addEventListener('focus', validateForm);

        // Menambahkan event listener klik pada ikon toggle password
        document.querySelector('.kata-sandi-child').addEventListener('click', togglePassword);
        document.querySelector('.kata-sandi-child1').addEventListener('click', togglePassword);
    });

    // Menambahkan event listener setelah DOM selesai dimuat untuk mengatur fokus pada input nama
    document.addEventListener("DOMContentLoaded", function () {
        const namaInput = document.getElementById('nama'); // Mendapatkan elemen input nama

        /**
         * Fungsi untuk mengatur fokus pada input nama setelah delay
         */
        function setFocus() {
            setTimeout(() => {
                namaInput.focus(); // Mengatur fokus pada input nama
            }, 300); // Menambahkan delay 300ms untuk memastikan modal sudah tampil
        }

        setFocus(); // Memanggil fungsi setFocus
    });
</script>

</html>
