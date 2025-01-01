<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
</head>

<body>
    <!-- Flashdata Messages sebagai Atribut Data -->
    <?php
    // Mendapatkan pesan sukses dari sesi flashdata
    $success_message = $this->session->flashdata('success');
    // Mendapatkan pesan error dari sesi flashdata
    $error_message = $this->session->flashdata('error');
    ?>
    <div id="flashdata"
        data-success="<?= isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-error="<?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <div class="lupa-passowrd unselectable-text">
        <div class="image-1-parent">
            <div class="sinkronisasi-aplikasi-mobile">Sinkronisasi Aplikasi Mobile dan Web</div>
            <!-- Gambar ilustrasi sinkronisasi dengan mencegah klik kanan -->
            <img oncontextmenu="return false;" class="illustrasi-sinkronisasi-icon" alt="" src="assets/img/illustrasi_sinkronisasi.svg">
            <div class="psa1ttmlqiu-1-parent">
                <div class="tersedia-juga-di">Tersedia juga di:</div>
                <!-- Ikon Play Store dengan mencegah klik kanan dan memberikan ID untuk interaksi JavaScript -->
                <img oncontextmenu="return false;" class="psa1ttmlqiu-1-icon" alt="" src="assets/img/icon_play_store.svg" id="psA1TtmLqiU1Image">
            </div>
        </div>
        <div class="login">
            <div class="logo-ls-1-parent">
                <!-- Logo Lesson Study dengan mencegah klik kanan -->
                <img oncontextmenu="return false;" class="logo-ls-1" alt="" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </div>
            <div class="heading-daftar-akun">
                <div class="lupa-kata-sandi-parent">
                    <div class="lupa-kata-sandi">Lupa Kata Sandi</div>
                    <div class="masukkan-alamat-e-mail">Masukkan alamat e-mail yang terdaftar untuk mendapatkan kata sandi baru.</div>
                </div>
            </div>
            <!-- Formulir untuk meminta reset kata sandi -->
            <form action="formLupaKataSandi" method="POST">
                <div class="input-field">
                    <div class="label">Alamat E-mail</div>
                    <div class="input-field-inner">
                        <div class="placeholder-wrapper">
                            <!-- Input email dengan validasi wajib diisi -->
                            <input type="email" id="email" name="email_address" class="placeholder" placeholder="Silakan masukkan alamat E-mail" required>
                        </div>
                    </div>
                </div>
                <!-- Tombol untuk mengirim permintaan reset kata sandi -->
                <button id="sendRequest" type="submit" class="button link">
                    <div class="ini-adalah-text">Kirim</div>
                </button>
            </form>
            <div class="ingat-kata-sandi">Ingat kata sandi?</div>
            <div class="login-child">
            </div>
            <!-- Tautan ke halaman login -->
            <a class="text-button link" href="pageLogin">
                <div class="ini-adalah-text">Masuk Disini</div>
            </a>
        </div>
    </div>
</body>

<script>
    // Menunggu hingga seluruh konten DOM dimuat
    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen dengan ID 'flashdata'
        var flashdata = document.getElementById('flashdata');
        // Mendapatkan atribut data-success dari elemen flashdata
        var success = flashdata.getAttribute('data-success');
        // Mendapatkan atribut data-error dari elemen flashdata
        var error = flashdata.getAttribute('data-error');

        // Menampilkan SweetAlert untuk pesan sukses jika ada
        if (success) {
            Swal.fire({
                icon: 'success', // Jenis ikon yang ditampilkan
                title: 'Berhasil', // Judul alert
                text: success, // Teks pesan sukses
                confirmButtonText: 'OK', // Teks tombol konfirmasi
                confirmButtonColor: '#2563EB' // Warna tombol konfirmasi
            });
        }

        // Menampilkan SweetAlert untuk pesan error jika ada
        if (error) {
            Swal.fire({
                icon: 'error', // Jenis ikon yang ditampilkan
                title: 'Error', // Judul alert
                text: error, // Teks pesan error
                confirmButtonText: 'OK', // Teks tombol konfirmasi
                confirmButtonColor: '#2563EB' // Warna tombol konfirmasi
            });
        }
    });

    // Mendapatkan elemen dengan ID 'psA1TtmLqiU1Image'
    var psA1TtmLqiU1Image = document.getElementById("psA1TtmLqiU1Image");
    // Memeriksa apakah elemen tersebut ada di dalam DOM
    if (psA1TtmLqiU1Image) {
        // Menambahkan event listener untuk klik pada gambar Play Store
        psA1TtmLqiU1Image.addEventListener("click", function() {
            // Membuka tautan Play Store dalam tab baru
            window.open("https://play.google.com/store/apps/details?id=feri.com.lesson");
        });
    }

    // Menunggu hingga seluruh konten DOM dimuat
    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen input email berdasarkan ID
        const emailInput = document.getElementById('email');

        // Fungsi untuk mengatur fokus pada input email setelah jeda
        function setFocus() {
            setTimeout(() => {
                emailInput.focus({ preventScroll: true }); // Mengatur fokus pada elemen input email
            }, 0);
        }

        setFocus(); // Memanggil fungsi setFocus
    });
</script>

</html>
