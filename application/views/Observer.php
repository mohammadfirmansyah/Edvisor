<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/observer.css" />
</head>

<body>
    <!-- Flashdata Messages sebagai Data Attributes -->
    <?php
    // Mendapatkan flashdata dari session
    $success_message = $this->session->flashdata('success');
    $error_message = $this->session->flashdata('error');
    ?>
    <!-- Menyimpan pesan flashdata dalam elemen tersembunyi -->
    <div id="flashdata"
        data-success="<?php echo isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-error="<?php echo isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <div class="observer content unselectable-text">
        <!-- Sidebar Navigasi -->
        <div class="sidebar">
            <a class="logo-ls-1-parent link" href="sidebarBeranda">
                <img oncontextmenu="return false;" class="logo-ls-1" alt="Logo Lesson Study"
                    src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="cari-disini">edvisor</b>
            </a>
            <a class="profile-side-bar link" href="sidebarProfile">
                <div class="profile-side-bar-child" alt="Foto Profil">
                    <img oncontextmenu="return false;" class="profile-photo"
                        src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>"
                        alt="Foto Profil">
                </div>
                <div class="nama-pengguna">
                    <?php
                    echo isset($user->full_name) ? htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8') : '';
                    ?>
                </div>
                <div class="email-pengguna">
                    <?php
                    echo isset($user->email_address) ? htmlspecialchars($user->email_address, ENT_QUOTES, 'UTF-8') : '';
                    ?>
                </div>
            </a>
            <div class="menu-bar">
                <a class="item-side-bar-default link" href="sidebarBeranda">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Beranda"
                        src="assets/img/icon_beranda.svg">
                    <div class="text-sidebar-default">Beranda</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarGuruModel">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Guru Model"
                        src="assets/img/icon_guru_model.svg">
                    <div class="text-sidebar-default">Guru Model</div>
                </a>
                <a class="item-side-bar-active link" href="sidebarObserver">
                    <img oncontextmenu="return false;" class="icon-sidebar-active" alt="Observer"
                        src="assets/img/icon_observer.svg">
                    <div class="text-sidebar-active">Observer</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarBantuan">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Bantuan"
                        src="assets/img/icon_bantuan.svg">
                    <div class="text-sidebar-default">Bantuan</div>
                </a>
            </div>
            <a class="item-side-bar-exit link" href="sidebarLogout">
                <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Keluar"
                    src="assets/img/icon_keluar.svg">
                <div class="text-sidebar-default">Keluar</div>
            </a>
        </div>
        <div class="observasi">Observasi</div>
        <!-- Tampilkan tanggal dan waktu -->
        <div class="date-container">
            <p id="dateDisplay" class="date"></p>
            <p id="timeDisplay" class="date"></p>
        </div>
        <div class="frame-parent">
            <div class="daftar-kelas-saya-parent">
                <div class="daftar-kelas-saya">Daftar Kelas Saya</div>
                <div class="frame-group">
                    <div class="search-parent">
                        <img oncontextmenu="return false;" class="notifications-active-icon1" alt="Icon Search"
                            src="assets/img/icon_search.svg">
                        <div class="cari-disini">
                            <div class="search-parent">
                                <input type="text" name="search" class="search-input" placeholder="Cari Disini....." />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tombol Gabung Kelas -->
                <div id="gabungKelas">
                    <div class="button">
                        <img oncontextmenu="return false;" class="sort-icon" alt="Tambah Kelas"
                            src="assets/img/icon_add.svg">
                        <div class="button1">Gabung Kelas</div>
                    </div>
                </div>
            </div>
            <div class="frame-container">
                <!-- Header Kolom -->
                <div class="nama-kelas-parent">
                    <div class="judul-nama-kelas unselectable-text" data-column="data-nama-kelas">Nama Kelas</div>
                    <div class="judul-mata-pelajaran unselectable-text" data-column="data-mata-pelajaran">Mata Pelajaran</div>
                    <div class="judul-guru-model unselectable-text" data-column="data-guru-model">Guru Model</div>
                    <div class="judul-observer unselectable-text">Observer</div>
                    <div class="judul-tanggal unselectable-text" data-column="tanggal-aktivitas-saya">Tanggal</div>
                    <div class="judul-status unselectable-text" data-column="isi-status">Status</div>
                </div>

                <!-- Kontainer Tabel Kelas -->
                <div class="frame-container1" id="table-container">
                    <?php if (!empty($classes)) : ?>
                        <?php foreach ($classes as $class): ?>
                            <a class="item-tabel link" href="<?php echo site_url('pageKelasObserver1/' . $class->encrypted_class_id); ?>">
                                <!-- Nama Kelas -->
                                <div class="data-nama-kelas">
                                    <?php
                                    echo isset($class->class_name) ? htmlspecialchars($class->class_name, ENT_QUOTES, 'UTF-8') : '';
                                    ?>
                                </div>

                                <!-- Mata Pelajaran -->
                                <div class="data-mata-pelajaran">
                                    <?php
                                    echo isset($class->subject) ? htmlspecialchars($class->subject, ENT_QUOTES, 'UTF-8') : '';
                                    ?>
                                </div>

                                <!-- Guru Model -->
                                <div class="data-guru-model">
                                    <?php
                                    echo isset($class->guru_model_name) ? htmlspecialchars($class->guru_model_name, ENT_QUOTES, 'UTF-8') : '';
                                    ?>
                                </div>

                                <!-- Observer Terbaru -->
                                <div class="profile-groupvariant9">
                                    <?php if (!empty($class->latest_observers)): ?>
                                        <?php foreach ($class->latest_observers as $observer): ?>
                                            <img oncontextmenu="return false;" class="profile-groupvariant9-item" alt="<?php echo isset($observer->full_name) ? htmlspecialchars($observer->full_name, ENT_QUOTES, 'UTF-8') : 'Observer'; ?>" src="<?php echo !empty($observer->src_profile_photo) ? base_url($observer->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>">
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Jika tidak ada observer, tampilkan placeholder atau kosong -->
                                        <img oncontextmenu="return false;" class="profile-groupvariant9-item" alt="No Observer" src="assets/default/default_profile_picture.jpg">
                                    <?php endif; ?>
                                </div>

                                <!-- Tanggal Aktivitas -->
                                <div class="tanggal-aktivitas-saya">
                                    <?php
                                    echo isset($class->date) ? htmlspecialchars(indonesian_date($class->date), ENT_QUOTES, 'UTF-8') : '';
                                    ?>
                                </div>

                                <!-- Status -->
                                <?php
                                // Menentukan kelas status berdasarkan status
                                switch ($class->status) {
                                    case 'Akan Datang':
                                        $status_class = 'status-label';
                                        break;
                                    case 'Belum Dilengkapi':
                                        $status_class = 'status-label2';
                                        break;
                                    case 'Selesai':
                                        $status_class = 'status-label3';
                                        break;
                                    default:
                                        $status_class = 'status-label';
                                        break;
                                }
                                ?>
                                <div class="<?php echo $status_class; ?>">
                                    <div class="isi-status">
                                        <?php
                                        echo isset($class->status) ? htmlspecialchars($class->status, ENT_QUOTES, 'UTF-8') : '';
                                        ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="empty_activity">Anda belum memiliki kelas observasi.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Gabung Kelas -->
    <div id="modalGabungKelas" class="modal-gabung-kelas unselectable-text">
        <div class="modal-gabung-kelas-inner">
            <div class="masukkan-kode-kelas">Masukkan Kode Kelas</div>
            <div class="kode-kelas-dapat">Kode kelas dapat diperoleh dari guru model</div>
            <div class="input-field">
                <div class="input-field-inner">
                    <div class="placeholder-wrapper">
                        <input type="text" id="kodeKelas" class="placeholder" name="class_code"
                            placeholder="Masukkan Kode Kelas...">
                    </div>
                </div>
            </div>
            <div id="tutupModalGabungKelas" class="batal-wrapper">
                <div class="batal link-cursor-hovering">Batal</div>
            </div>
            <div id="cariKelas" class="button2 link">
                <div class="button1">Cari</div>
            </div>
        </div>
    </div>

    <!-- Formulir Observer Baru -->
    <form enctype="multipart/form-data" action="simpanDataObserver" method="POST" id="formObserverBaru">
        <!-- Pop-up Tambah Observer -->
        <div class="pop-up-tambah-observer-parent popup-hidden unselectable-text" id="popUpFormulirObserverBaru">
            <div class="pop-up-tambah-observer">
                <div class="pilih-observer">Kelas Ditemukan!</div>
                <div class="pilih-observer1">Guru Model:&nbsp;<div id="namaGuruModel" class="pilih-observer2"></div>
                </div>
                <input type="hidden" id="nomorSiswaId" name="nomorSiswaId">
                <input type="hidden" id="nomorTidakTersedia" name="nomorTidakTersedia">
                <!-- Tambahkan input tersembunyi untuk class_code -->
                <input type="hidden" id="classCode" name="class_code">
                <div class="pilih-siswa">
                    <!-- Nomor siswa akan ditampilkan di sini -->
                </div>
                <div class="keterangan">Keterangan:</div>
                <div class="rectangle-parent">
                    <div class="rectangle-div">
                    </div>
                    <div class="tidak-tersedia">Tidak Tersedia</div>
                </div>
                <div class="rectangle-group">
                    <div class="group-child1">
                    </div>
                    <div class="tidak-tersedia">Tersedia</div>
                </div>
                <div class="rectangle-container">
                    <div class="group-child2">
                    </div>
                    <div class="tidak-tersedia">Dipilih</div>
                </div>
                <div class="text-button link link-cursor-hovering" id="batalPilihObserver">
                    <div class="div">Batal</div>
                </div>
                <button disabled type="submit" class="button3 link" id="simpanObserver">
                    <div class="button1">Simpan</div>
                </button>
            </div>
        </div>
    </form>

    <!-- Overlay -->
    <div id="overlay" class="overlay-hidden"></div>
</body>

<script>
    /**
     * Fungsi untuk mempersiapkan animasi scroll pada elemen tertentu
     * @param {HTMLElement} element - Elemen yang akan dianimasikan
     */
    function setupScrollAnimation(element) {
        // Fungsi untuk memeriksa apakah elemen benar-benar overflow
        function isElementOverflowing(el) {
            const scrollWidth = el.scrollWidth;
            const clientWidth = el.clientWidth;
            const difference = scrollWidth - clientWidth;
            const overflowing = scrollWidth > clientWidth + 1; // Toleransi 1px

            return {
                overflowing,
                scrollWidth,
                clientWidth,
                difference
            };
        }

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
            let animationId = null; // Ubah dari const ke let

            /**
             * Fungsi animasi menggunakan requestAnimationFrame
             * @param {number} timestamp - Waktu saat frame animasi dimulai
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

        // Tambahkan event listener untuk memulai dan menghentikan animasi saat hover
        element.addEventListener("mouseenter", startScroll);
        element.addEventListener("mouseleave", stopScroll);
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen flashdata
        var flashdata = document.getElementById('flashdata');
        var success = flashdata.getAttribute('data-success');
        var error = flashdata.getAttribute('data-error');

        // Variabel untuk input tersembunyi nomorSiswaId
        const nomorSiswaIdInput = document.getElementById('nomorSiswaId');

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

        /**
         * Fungsi untuk memperbarui tanggal dan waktu secara real-time
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

            // Mengupdate elemen HTML
            document.getElementById('dateDisplay').innerText = dateString;
            document.getElementById('timeDisplay').innerText = timeString;
        }

        // Memanggil fungsi updateDateTime setiap detik
        setInterval(updateDateTime, 1000);

        // Memastikan waktu saat ini ditampilkan saat memuat halaman
        updateDateTime();

        // Menangani fitur pencarian
        const searchInput = document.querySelector('.search-input');
        const tabelLinks = document.querySelectorAll('.item-tabel');

        searchInput.addEventListener('input', function() {
            const searchQuery = searchInput.value.toLowerCase();

            tabelLinks.forEach(link => {
                const namaKelas = link.querySelector('.data-nama-kelas').textContent.toLowerCase();
                const mataPelajaran = link.querySelector('.data-mata-pelajaran').textContent.toLowerCase();
                const guruModel = link.querySelector('.data-guru-model').textContent.toLowerCase();
                const tanggal = link.querySelector('.tanggal-aktivitas-saya').textContent.toLowerCase();
                const status = link.querySelector('.isi-status').textContent.toLowerCase();

                // Tampilkan link jika query pencarian cocok dengan salah satu field
                if (namaKelas.includes(searchQuery) || mataPelajaran.includes(searchQuery) ||
                    guruModel.includes(searchQuery) || tanggal.includes(searchQuery) ||
                    status.includes(searchQuery)) {
                    link.style.display = '';
                } else {
                    link.style.display = 'none';
                }
            });
        });

        // Mengatur popup dan overlay
        var popup = document.getElementById('modalGabungKelas');
        var observerFormPopup = document.getElementById('popUpFormulirObserverBaru');
        var overlay = document.getElementById('overlay');
        const kodeKelasInput = document.getElementById('kodeKelas');
        const namaGuruModelSpan = document.getElementById('namaGuruModel');
        const classCodeInput = document.getElementById('classCode'); // Input tersembunyi untuk class_code

        // Pastikan popup dan overlay tersembunyi saat halaman dimuat
        popup.classList.add('popup-hidden');
        observerFormPopup.classList.add('popup-hidden');
        overlay.classList.add('overlay-hidden');

        // Event listener untuk menampilkan popup Gabung Kelas
        document.getElementById('gabungKelas').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default
            // Tutup popup lain jika terbuka
            observerFormPopup.classList.remove('popup-visible');
            observerFormPopup.classList.add('popup-hidden');

            // Tampilkan popup Gabung Kelas
            popup.classList.remove('popup-hidden');
            popup.classList.add('popup-visible');
            overlay.classList.remove('overlay-hidden');
            overlay.classList.add('overlay-visible');
            document.body.classList.add('blur'); // Tambahkan efek blur pada latar belakang
            setTimeout(() => {
                kodeKelasInput.focus();
            }, 300); // Delay agar modal benar-benar terlihat sebelum fokus
        });

        // Event listener untuk menutup popup Gabung Kelas
        document.getElementById('tutupModalGabungKelas').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default
            popup.classList.remove('popup-visible');
            popup.classList.add('popup-hidden');
            overlay.classList.remove('overlay-visible');
            overlay.classList.add('overlay-hidden');
            document.body.classList.remove('blur'); // Hapus efek blur dari latar belakang
            // Reset input kode kelas
            kodeKelasInput.value = '';
        });

        // Event listener untuk menutup popup ketika klik di luar modal
        overlay.addEventListener('click', function(event) {
            // Tutup semua pop-up jika ada yang terbuka
            popup.classList.remove('popup-visible');
            popup.classList.add('popup-hidden');
            observerFormPopup.classList.remove('popup-visible');
            observerFormPopup.classList.add('popup-hidden');
            overlay.classList.remove('overlay-visible');
            overlay.classList.add('overlay-hidden');
            document.body.classList.remove('blur'); // Hapus efek blur dari latar belakang
        });

        // Mencegah klik di dalam popup dari menutup popup
        popup.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        observerFormPopup.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        // Event listener untuk tombol Cari Kelas menggunakan AJAX
        document.getElementById('cariKelas').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default
            const classCode = kodeKelasInput.value.trim();

            // Kirim AJAX request untuk memeriksa kode kelas
            if (classCode === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Anda harus mengisi kode',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563EB'
                });
                return;
            }

            // Tampilkan loading indicator
            Swal.fire({
                title: 'Mencari Kelas...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('<?php echo site_url("formGabungKelasObserver"); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'class_code=' + encodeURIComponent(classCode)
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close(); // Tutup loading indicator
                    if (data.status === 'success') {
                        // Tutup popup Gabung Kelas
                        popup.classList.remove('popup-visible');
                        popup.classList.add('popup-hidden');

                        // Tampilkan pop-up formulir observer baru
                        observerFormPopup.classList.remove('popup-hidden');
                        observerFormPopup.classList.add('popup-visible');
                        overlay.classList.remove('overlay-hidden');
                        overlay.classList.add('overlay-visible');

                        // Tampilkan nama guru model
                        namaGuruModelSpan.textContent = (typeof data.guru_model !== 'undefined' && data.guru_model !== null) ? data.guru_model : '';

                        // Atur animasi pada elemen namaGuruModel setelah teks diisi
                        setupScrollAnimation(namaGuruModelSpan);

                        // Set nilai class_code pada input tersembunyi
                        classCodeInput.value = classCode;

                        // Render nomor siswa
                        renderStudentNumbers(data.assigned_students);
                    } else {
                        // Tampilkan pesan error sesuai dengan jenis kesalahan
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2563EB'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    showSweetAlert({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mencari kelas.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563EB'
                    });
                });
        });

        /**
         * Fungsi untuk menampilkan SweetAlert tanpa menimbulkan konflik dengan overlay kustom.
         * @param {Object} options - Objek konfigurasi untuk SweetAlert.
         * @returns {Promise} Promise dari Swal.fire dengan result yang diteruskan.
         */
        function showSweetAlert(options) {
            // Cek apakah overlay kustom sedang terlihat
            var overlay = document.getElementById('overlay');
            var wasOverlayVisible = overlay.classList.contains('overlay-visible');

            // Sembunyikan overlay kustom jika sedang terlihat
            if (wasOverlayVisible) {
                overlay.classList.remove('overlay-visible');
                overlay.classList.add('overlay-hidden');
            }

            // Tampilkan SweetAlert dengan opsi yang diberikan dan kembalikan promise-nya
            return Swal.fire(options).then((result) => {
                // Setelah SweetAlert ditutup, kembalikan overlay kustom jika sebelumnya terlihat
                if (wasOverlayVisible) {
                    overlay.classList.remove('overlay-hidden');
                    overlay.classList.add('overlay-visible');
                }
                return result; // Penting: meneruskan result ke pemanggil
            });
        }

        /**
         * Fungsi untuk merender nomor siswa yang bisa dipilih
         * @param {Array} assignedStudents - Daftar nomor siswa yang sudah dipilih oleh observer lain.
         */
        function renderStudentNumbers(assignedStudents) {
            const pilihSiswaContainer = document.querySelector(".pilih-siswa");
            pilihSiswaContainer.innerHTML = ''; // Bersihkan konten sebelumnya

            // Tambahkan judul "Pilih Siswa Yang Diamati"
            const pilihSiswaYangDiv = document.createElement("div");
            pilihSiswaYangDiv.classList.add("pilih-siswa-yang");
            pilihSiswaYangDiv.textContent = "Pilih Siswa Yang Diamati";
            pilihSiswaContainer.appendChild(pilihSiswaYangDiv);

            // Tambahkan informasi jumlah siswa yang dipilih
            const infoPemilihan = document.createElement("div");
            infoPemilihan.classList.add("info-pemilihan");
            infoPemilihan.textContent = `Siswa Dipilih: ${selectedNomorSiswa.length} / 12`;
            pilihSiswaContainer.appendChild(infoPemilihan);

            // Mendapatkan elemen input tersembunyi nomorSiswaId
            const nomorSiswaIdInput = document.getElementById('nomorSiswaId');

            // Loop untuk membuat daftar nomor siswa
            for (let i = 1; i <= 100; i++) {
                const nomor = i.toString().padStart(2, "0");
                const siswaDiv = document.createElement("div");
                siswaDiv.setAttribute("data-nomor-id", i);

                if (assignedStudents.includes(i.toString())) {
                    siswaDiv.classList.add("nomor-siswa-tidak-tersedia");
                    siswaDiv.innerHTML = `
                <div class="nomor-siswa-tidak-tersedia-item">
                    <div class="div">${nomor}</div>
                </div>
                <div class="frame-group">
                    <div class="siswa-diamati-observer-lain-wrapper">
                        <div class="siswa-diamati-observer-lain">Siswa diamati observer lain</div>
                    </div>
                    <img oncontextmenu="return false;" class="group-child3" alt="Observer Lain" src="assets/img/polygon.svg">
                </div>`;
                } else {
                    siswaDiv.classList.add("nomor-siswa-tersedia");
                    siswaDiv.innerHTML = `
                <div class="nomor-siswa-tersedia-item">
                    <div class="div">${nomor}</div>
                </div>`;
                }

                // Tambahkan event listener untuk mengubah status nomor siswa saat diklik
                siswaDiv.addEventListener("click", function() {
                    const siswaId = this.getAttribute("data-nomor-id");
                    const nomor = siswaId.padStart(2, "0");

                    // Jika siswa tersedia dan belum mencapai batas pemilihan
                    if (this.classList.contains("nomor-siswa-tersedia")) {
                        if (selectedNomorSiswa.length >= 12) {
                            // Tampilkan SweetAlert peringatan batas tercapai
                            showSweetAlert({
                                icon: 'warning',
                                title: 'Batas Tercapai',
                                text: 'Anda hanya dapat memilih maksimal 12 siswa untuk setiap observer.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#2563EB'
                            });
                            return; // Hentikan pemilihan tambahan
                        }

                        // Tandai siswa sebagai dipilih
                        this.classList.remove("nomor-siswa-tersedia");
                        this.classList.add("nomor-siswa-aktif");
                        this.innerHTML = `
                    <div class="nomor-siswa-aktif-item">
                        <div class="div">${nomor}</div>
                    </div>`;
                        selectedNomorSiswa.push(siswaId);
                    }
                    // Jika siswa sedang dipilih dan ingin dibatalkan
                    else if (this.classList.contains("nomor-siswa-aktif")) {
                        // Tandai siswa sebagai tersedia kembali
                        this.classList.remove("nomor-siswa-aktif");
                        this.classList.add("nomor-siswa-tersedia");
                        this.innerHTML = `
                    <div class="nomor-siswa-tersedia-item">
                        <div class="div">${nomor}</div>
                    </div>`;
                        // Hapus siswa dari daftar yang dipilih
                        const idx = selectedNomorSiswa.indexOf(siswaId);
                        if (idx !== -1) {
                            selectedNomorSiswa.splice(idx, 1);
                        }
                    }

                    // Update nilai input tersembunyi dan informasi pemilihan
                    nomorSiswaIdInput.value = selectedNomorSiswa.join(",");
                    infoPemilihan.textContent = `Siswa Dipilih: ${selectedNomorSiswa.length} / 12`; // Update informasi
                    updateButtonState();
                    console.log("Selected Nomor Siswa IDs:", nomorSiswaIdInput.value);
                });

                // Tambahkan elemen siswa ke container
                pilihSiswaContainer.appendChild(siswaDiv);
            }
        }

        // Inisialisasi array untuk nomor siswa yang dipilih
        let selectedNomorSiswa = [];

        // Event listener untuk tombol Batal pada formulir observer baru
        document.getElementById('batalPilihObserver').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default
            const observerFormPopup = document.getElementById('popUpFormulirObserverBaru');
            observerFormPopup.classList.remove('popup-visible');
            observerFormPopup.classList.add('popup-hidden');
            overlay.classList.remove('overlay-visible');
            overlay.classList.add('overlay-hidden');
            document.body.classList.remove('blur'); // Hapus efek blur dari latar belakang
            // Reset pilihan siswa
            selectedNomorSiswa = [];
            updateButtonState();
        });

        /**
         * Event listener untuk tombol Simpan Observer
         */
        document.getElementById('formObserverBaru').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah aksi default form submission

            // Validasi jumlah siswa yang dipilih
            if (selectedNomorSiswa.length === 0) {
                // Jika tidak ada siswa yang dipilih, tampilkan pesan error
                showSweetAlert({
                    icon: 'error',
                    title: 'Error',
                    text: 'Anda harus memilih setidaknya satu siswa.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563EB'
                });
                return;
            }

            if (selectedNomorSiswa.length > 12) {
                // Jika jumlah siswa yang dipilih melebihi 12, tampilkan pesan peringatan
                showSweetAlert({
                    icon: 'error',
                    title: 'Jumlah Siswa Berlebih',
                    text: 'Anda hanya dapat memilih maksimal 12 siswa untuk setiap observer.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563EB'
                });
                return;
            }

            // Tampilkan loading indicator
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Kirim data melalui AJAX ke metode 'simpanDataObserver'
            const formData = new FormData(this);
            formData.append('nomorSiswaId', selectedNomorSiswa.join(','));

            fetch('<?php echo site_url("simpanDataObserver"); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close(); // Tutup loading indicator
                    if (data.status === 'success') {
                        // Tutup pop-up dan overlay
                        observerFormPopup.classList.remove('popup-visible');
                        observerFormPopup.classList.add('popup-hidden');
                        overlay.classList.remove('overlay-visible');
                        overlay.classList.add('overlay-hidden');
                        document.body.classList.remove('blur');

                        // Tampilkan pesan sukses dan redirect
                        showSweetAlert({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2563EB'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect ke halaman yang ditentukan
                                window.location.href = data.redirect_url;
                            }
                        });
                    } else {
                        // Tampilkan pesan error
                        showSweetAlert({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2563EB'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    showSweetAlert({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menyimpan data observer.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563EB'
                    });
                });
        });

        /**
         * Fungsi untuk memperbarui status tombol Simpan
         */
        function updateButtonState() {
            const simpanButton = document.getElementById("simpanObserver");
            if (selectedNomorSiswa.length > 0) {
                simpanButton.disabled = false;
                simpanButton.classList.remove("disabled");
            } else {
                simpanButton.disabled = true;
                simpanButton.classList.add("disabled");
            }
        }

        /**
         * Fungsi untuk log status nomor siswa yang tersedia, dipilih, dan tidak tersedia
         */
        function logNomorStatus() {
            const tersedia = [];
            const dipilih = selectedNomorSiswa.map((num) => parseInt(num, 10));
            const tidakTersedia = document.querySelectorAll(".nomor-siswa-tidak-tersedia");
            let tidakTersediaList = Array.from(tidakTersedia).map(el => parseInt(el.getAttribute("data-nomor-id"), 10));

            document.querySelectorAll(".nomor-siswa-tersedia").forEach(el => {
                tersedia.push(parseInt(el.getAttribute("data-nomor-id"), 10));
            });

            console.log(`Tersedia: ${tersedia.join(", ")}`);
            console.log(`Dipilih: ${dipilih.join(", ")}`);
            console.log(`Tidak Tersedia: ${tidakTersediaList.join(", ")}`);
        }

        // Initial call to log status
        logNomorStatus();

        /**
         * Fungsi untuk memperbarui dan menangani animasi pada elemen yang memiliki kelas tertentu
         */
        function initializeAnimations() {
            // Daftar kelas atau atribut data yang akan dianimasikan secara langsung
            const directClassesToAnimate = [
                "data-nama-kelas",
                "data-mata-pelajaran",
                "data-guru-model",
                "tanggal-aktivitas-saya",
                "nama-pengguna",
                "email-pengguna",
                "pilih-observer2"
            ];

            // Mengolah elemen-elemen yang dianimasikan secara langsung berdasarkan kelas
            directClassesToAnimate.forEach(className => {
                // Menggunakan selector kelas
                const elements = document.querySelectorAll(`.${className}`);

                elements.forEach(element => {
                    setupScrollAnimation(element); // Mengatur animasi pada elemen
                });
            });
        }

        // Inisialisasi animasi saat halaman dimuat
        initializeAnimations();
    });

    document.addEventListener("DOMContentLoaded", function() {
        const tableContainer = document.getElementById("table-container");
        const headers = document.querySelectorAll(".nama-kelas-parent > div[data-column]");
        let currentSort = {
            column: '',
            order: 'asc',
            clicks: {}
        };
        const originalOrder = Array.from(tableContainer.getElementsByClassName("item-tabel"));

        // Menambahkan event listener untuk sorting kolom
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const column = header.getAttribute('data-column');
                handleSort(column, header);
            });
        });

        /**
         * Fungsi untuk menangani sorting tabel
         * @param {string} column - Nama kolom yang akan di-sort
         * @param {HTMLElement} header - Elemen header yang diklik
         */
        function handleSort(column, header) {
            const items = Array.from(tableContainer.getElementsByClassName("item-tabel"));

            if (!currentSort.clicks[column]) {
                currentSort.clicks[column] = 0;
            }
            currentSort.clicks[column]++;

            if (currentSort.column === column) {
                if (column === 'isi-status') {
                    if (currentSort.clicks[column] === 1) {
                        currentSort.statusStep = 1;
                    } else if (currentSort.clicks[column] === 2) {
                        currentSort.statusStep = 2;
                    } else if (currentSort.clicks[column] === 3) {
                        currentSort.statusStep = 3;
                    } else {
                        currentSort.clicks[column] = 0;
                        currentSort.statusStep = 0;
                        resetTable();
                        return;
                    }
                } else {
                    if (currentSort.clicks[column] === 1) {
                        currentSort.order = 'asc';
                    } else if (currentSort.clicks[column] === 2) {
                        currentSort.order = 'desc';
                    } else {
                        currentSort.order = 'default';
                        currentSort.clicks[column] = 0;
                        resetTable();
                        return;
                    }
                }
            } else {
                currentSort.column = column;
                currentSort.clicks = {
                    [column]: 1
                };
                if (column === 'isi-status') {
                    currentSort.statusStep = 1;
                } else {
                    currentSort.order = 'asc';
                }
            }

            if (column === 'isi-status') {
                let priority = [];
                if (currentSort.statusStep === 1) {
                    priority = ['Akan Datang', 'Belum Dilengkapi', 'Selesai'];
                } else if (currentSort.statusStep === 2) {
                    priority = ['Belum Dilengkapi', 'Akan Datang', 'Selesai'];
                } else if (currentSort.statusStep === 3) {
                    priority = ['Selesai', 'Akan Datang', 'Belum Dilengkapi'];
                }

                items.sort((a, b) => {
                    const aStatus = a.getElementsByClassName('isi-status')[0].innerText.trim();
                    const bStatus = b.getElementsByClassName('isi-status')[0].innerText.trim();

                    return priority.indexOf(aStatus) - priority.indexOf(bStatus);
                });
            } else {
                items.sort((a, b) => {
                    const aText = a.querySelector('.' + column).innerText.trim();
                    const bText = b.querySelector('.' + column).innerText.trim();

                    if (column === 'tanggal-aktivitas-saya') {
                        const aDate = parseDate(aText);
                        const bDate = parseDate(bText);
                        return currentSort.order === 'asc' ? aDate - bDate : bDate - aDate;
                    }

                    if (aText > bText) {
                        return currentSort.order === 'asc' ? 1 : -1;
                    } else if (aText < bText) {
                        return currentSort.order === 'asc' ? -1 : 1;
                    } else {
                        return 0;
                    }
                });
            }

            // Menghapus konten tabel dan menambahkan yang sudah di-sort
            while (tableContainer.firstChild) {
                tableContainer.removeChild(tableContainer.firstChild);
            }

            items.forEach(item => tableContainer.appendChild(item));
            updateHeaders(header);
        }

        /**
         * Fungsi untuk mengurai tanggal dari format Indonesia
         * @param {string} dateString - Tanggal dalam format "dd MMMM yyyy"
         * @returns {Date} Objek Date
         */
        function parseDate(dateString) {
            const monthMap = {
                "Januari": 0,
                "Februari": 1,
                "Maret": 2,
                "April": 3,
                "Mei": 4,
                "Juni": 5,
                "Juli": 6,
                "Agustus": 7,
                "September": 8,
                "Oktober": 9,
                "November": 10,
                "Desember": 11
            };
            const parts = dateString.split(' ');
            const day = parseInt(parts[0], 10);
            const month = monthMap[parts[1]];
            const year = parseInt(parts[2], 10);
            return new Date(year, month, day);
        }

        /**
         * Fungsi untuk memperbarui tampilan header kolom yang aktif
         * @param {HTMLElement} activeHeader - Elemen header yang aktif
         */
        function updateHeaders(activeHeader) {
            headers.forEach(header => {
                header.innerText = header.innerText.replace(/[\u2191\u2193]/g, '');
            });
            if (currentSort.column === 'isi-status' && currentSort.clicks['isi-status'] > 0) {
                activeHeader.innerText += ' \u2193';
            } else if (currentSort.order !== 'default') {
                activeHeader.innerText += currentSort.order === 'asc' ? ' \u2191' : ' \u2193';
            }
        }

        /**
         * Fungsi untuk mereset tabel ke urutan asli
         */
        function resetTable() {
            while (tableContainer.firstChild) {
                tableContainer.removeChild(tableContainer.firstChild);
            }

            originalOrder.forEach(item => tableContainer.appendChild(item));
            headers.forEach(header => {
                header.innerText = header.innerText.replace(/[\u2191\u2193]/g, '');
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('.search-input');

        /**
         * Fungsi untuk mengatur fokus ke input pencarian setelah delay
         */
        function setFocus() {
            setTimeout(() => {
                searchInput.focus();
            }, 300); // Delay agar input focus setelah modal tampil
        }

        setFocus();
    });
</script>

</html>