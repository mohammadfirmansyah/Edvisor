<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo $title; ?></title>
    <base href="<?= base_url(); ?>">
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/editkelas3.css" />
</head>

<body>
    <!-- Flashdata Messages sebagai Atribut Data -->
    <?php
    // Mendapatkan flashdata dari sesi
    $success_message = $this->session->flashdata('success');
    $error_message = $this->session->flashdata('error');

    // Mempersiapkan data untuk JavaScript
    $usersData = isset($users) ? $users : [];
    $classCode = isset($class_code) ? $class_code : '';
    $userId = isset($user->user_id) ? $user->user_id : null;

    // Mengambil data observer yang sudah disimpan di database
    $savedObservers = isset($saved_observers) ? $saved_observers : [];
    ?>
    <div id="flashdata"
        data-success="<?= isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-error="<?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <div class="buat-kelas-3 content unselectable-text">
        <!-- Sidebar Navigasi -->
        <div class="sidebar">
            <a class="logo-ls-1-parent link" href="sidebarBeranda">
                <img oncontextmenu="return false;" class="logo-ls-1" alt="" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </a>
            <a class="profile-side-bar link" href="sidebarProfile">
                <div class="profile-side-bar-child" alt="">
                    <img oncontextmenu="return false;" class="profile-photo"
                        src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>"
                        alt="">
                </div>
                <div class="nama-pengguna"><?= htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="email-pengguna"><?= htmlspecialchars($user->email_address, ENT_QUOTES, 'UTF-8'); ?></div>
            </a>
            <div class="menu-bar">
                <a class="item-side-bar-default link" href="sidebarBeranda">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_beranda.svg">
                    <div class="text-sidebar-default">Beranda</div>
                </a>
                <a class="item-side-bar-active link" href="sidebarGuruModel">
                    <img oncontextmenu="return false;" class="icon-sidebar-active" alt="" src="assets/img/icon_guru_model.svg">
                    <div class="text-sidebar-active">Guru Model</div>
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
            <a class="item-side-bar-exit link" href="sidebarLogout">
                <img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_keluar.svg">
                <div class="text-sidebar-default">Keluar</div>
            </a>
        </div>

        <!-- Link untuk kembali ke halaman sebelumnya -->
        <a class="buat-kelas-group link"
            href="<?= site_url('pageKelasGuruModel/' . htmlspecialchars($encrypted_idKelas, ENT_QUOTES, 'UTF-8')); ?>">
            <img oncontextmenu="return false;" class="iconsolidarrow-left" alt="" src="assets/img/icon_arrow_left.svg">
            <div class="buat-kelas1">Edit Kelas
            </div>
        </a>

        <!-- Tampilkan tanggal dan waktu -->
        <div class="date-container">
            <p id="dateDisplay" class="date"></p>
            <p id="timeDisplay" class="date"></p>
        </div>

        <!-- Konten Utama Edit Kelas -->
        <div class="buat-kelas-parent">
            <div class="buat-kelas">Edit Kelas</div>
            <div class="detail-kelas-parent">
                <a class="detail-kelas link link-color-unset"
                    href="<?= site_url('pageEditKelas_detailKelas/' . htmlspecialchars($encrypted_idKelas, ENT_QUOTES, 'UTF-8')); ?>">
                    <ol class="detail-kelas1">
                        <li>Detail Kelas</li>
                    </ol>
                    <div class="group-child"></div>
                </a>
                <a class="unggah-berkas link link-color-unset"
                    href="<?= site_url('pageEditKelas_unggahBerkas/' . htmlspecialchars($encrypted_idKelas, ENT_QUOTES, 'UTF-8')); ?>">
                    <ol class="detail-kelas1">
                        <li>Unggah Berkas</li>
                    </ol>
                    <div class="group-item"></div>
                </a>
                <div class="detail-observer">
                    <ol class="detail-kelas1">
                        <li>Detail Observer</li>
                    </ol>
                    <div class="group-inner"></div>
                </div>
            </div>

            <!-- Form untuk mengirim data observer ke server -->
            <form id="formUpdateObserver" method="POST"
                action="<?= site_url('formUpdateDetailObserver'); ?>">

                <!-- Tombol dan Daftar Observer yang Sudah Dipilih -->
                <div class="button-parent">
                    <div class="item-observer-parent">
                        <!-- Daftar observer yang sudah dipilih akan ditampilkan di sini -->
                        <?php foreach ($savedObservers as $observer): ?>
                            <div class="item-observer" data-observer-id="<?= $observer['observerId']; ?>">
                                <img oncontextmenu="return false;" class="ellipse-icon" alt=""
                                    src="<?php echo !empty($observer['src_profile_photo']) ? base_url($observer['src_profile_photo']) : base_url('assets/default/default_profile_picture.jpg'); ?>">
                                <div class="bagas-nugroho"><?= htmlspecialchars($observer['observerName'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <div class="daftar-nomor-siswa">
                                    <?php
                                    // Mengurutkan nomor siswa dari terkecil ke terbesar
                                    $student_numbers = explode(',', $observer['nomorSiswa']);
                                    $student_numbers = array_map('trim', $student_numbers);
                                    sort($student_numbers, SORT_NUMERIC);
                                    ?>
                                    <?php foreach ($student_numbers as $num): ?>
                                        <div class="nomor-siswa">
                                            <div class="nomor-siswa-item">
                                                <div class="div"><?= str_pad(trim($num), 2, "0", STR_PAD_LEFT); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="download-button">
                                    <div class="download-button-child"></div>
                                    <img oncontextmenu="return false;" class="iconoutlinetrash" alt="" src="assets/img/trash_button.svg">
                                    <div class="frame-parent">
                                        <div class="unduh-wrapper">
                                            <div class="unduh">Hapus</div>
                                        </div>
                                        <img oncontextmenu="return false;" class="polygon-icon" alt="" src="assets/img/polygon.svg">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- Tombol Tambah Observer -->
                        <div class="text-button-icon" id="tambahObserver">
                            <img oncontextmenu="return false;" class="add-box-icon" alt="" src="assets/img/icon_add_observer.svg">
                            <div class="tambah-observer">Tambah Observer</div>
                        </div>
                    </div>

                    <!-- Tombol Simpan Kelas -->
                    <button disabled type="button" class="button link" id="simpanKelas">
                        <div class="button1">Simpan</div>
                    </button>
                </div>

                <!-- Input tersembunyi untuk mengirim data ke server -->
                <input type="hidden" name="observersData" id="observersData">
                <input type="hidden" name="class_id"
                    value="<?= htmlspecialchars($encrypted_idKelas, ENT_QUOTES, 'UTF-8'); ?>">
            </form>
        </div>
    </div>

    <!-- Pop-up Tambah Observer -->
    <div class="pop-up-tambah-observer-parent" id="popUpTambahObserverParent">
        <div class="pop-up-tambah-observer">
            <div class="pilih-observer">Pilih Observer</div>
            <div class="tambah-observer1">
                <div class="input-field">
                    <div class="frame-container">
                        <div class="iconsolidsearch-parent">
                            <img oncontextmenu="return false;" class="iconsolidsearch" alt="" src="assets/img/icon_search.svg">
                            <input type="text" id="pilihObserver" name="cariObserver" class="placeholder"
                                placeholder="Pilih Observer">
                            <input type="hidden" id="observerId" name="observerId">
                        </div>
                        <img oncontextmenu="return false;" class="iconoutlinechevron-down" alt="" src="assets/img/icon_down_detail_observer.svg">
                    </div>
                </div>
            </div>
            <div class="tambah-observer2">
                <div class="item-observer-group">
                    <!-- Daftar observer akan ditampilkan di sini -->
                </div>
            </div>
            <input type="hidden" id="nomorSiswaId" name="nomorSiswaId">
            <input type="hidden" id="nomorTidakTersedia" name="nomorTidakTersedia">
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
            <button disabled type="button" class="button2 link" id="simpanObserver">
                <div class="button1">Simpan</div>
            </button>
        </div>
    </div>
    <div id="overlay" class="overlay-hidden"></div>
</body>

<!-- Mendefinisikan variabel JavaScript dari PHP -->
<script>
    // Mengonversi data PHP ke variabel JavaScript
    var usersData = <?php echo json_encode($usersData); ?>;
    var classCode = "<?php echo $classCode; ?>";
    var userId = <?php echo json_encode($userId); ?>;
    var savedObservers = <?php echo json_encode($savedObservers); ?>;
    var encrypted_idKelas = "<?php echo htmlspecialchars($encrypted_idKelas, ENT_QUOTES, 'UTF-8'); ?>";
</script>

<!-- Memasukkan library jQuery dan SweetAlert jika belum ada -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    /**
     * Fungsi untuk memeriksa apakah elemen benar-benar overflow
     * @param {HTMLElement} element - Elemen HTML yang ingin diperiksa overflow-nya
     * @returns {Object} - Objek yang berisi informasi overflow
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
     * @param {HTMLElement} element - Elemen HTML yang akan dianimasikan
     */
    function setupScrollAnimation(element) {
        // Simpan teks asli dalam data attribute untuk pemulihan nanti
        const originalText = element.textContent.trim();
        element.setAttribute('data-original-text', originalText);

        // Terapkan gaya default dengan ellipsis
        element.style.overflow = "hidden";
        element.style.textOverflow = "ellipsis";
        element.style.whiteSpace = "nowrap";

        // Fungsi untuk memulai animasi scroll
        function startScroll() {
            if (element.getAttribute('data-animating') === 'true') return;

            const overflowInfo = isElementOverflowing(element);
            if (!overflowInfo.overflowing) return; // Tidak overflow, tidak perlu animasi

            element.setAttribute('data-animating', 'true');

            // Hilangkan text-overflow: ellipsis saat animasi berjalan
            element.style.textOverflow = "unset";
            element.style.whiteSpace = "nowrap";
            element.style.overflow = "hidden";

            // Ganti innerHTML dengan scroll-container dan scroll-text
            element.innerHTML =
                `<div class="scroll-container">
                    <span class="scroll-text">${originalText}&nbsp;&nbsp;&nbsp;</span>
                    <span class="scroll-text">${originalText}&nbsp;&nbsp;&nbsp;</span>
                </div>`;

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

            // Fungsi animasi menggunakan requestAnimationFrame
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

        // Tambahkan event listener untuk hover selalu, tanpa memeriksa overflow saat inisialisasi
        element.addEventListener("mouseenter", function() {
            element.startScroll();
        });

        element.addEventListener("mouseleave", function() {
            element.stopScroll();
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen-elemen yang diperlukan
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

        // Fungsi untuk memperbarui tanggal dan waktu setiap detik
        /**
         * Fungsi untuk memperbarui tampilan tanggal dan waktu
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

        // Mengelola pop-up Tambah Observer dan Overlay
        var popupTambahObserver = document.getElementById('popUpTambahObserverParent');
        var overlay = document.getElementById('overlay');

        // Pastikan pop-up dan overlay tersembunyi saat halaman dimuat
        popupTambahObserver.classList.add('popup-hidden');
        overlay.classList.add('overlay-hidden');

        // Menambahkan event listener untuk menampilkan pop-up Tambah Observer
        document.getElementById('tambahObserver').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default
            popupTambahObserver.classList.remove('popup-hidden');
            popupTambahObserver.classList.add('popup-visible');
            overlay.classList.remove('overlay-hidden');
            overlay.classList.add('overlay-visible');
            document.body.classList.add('blur'); // Tambahkan efek blur ke latar belakang

            // Panggil renderStudentNumbers untuk memperbarui tampilan nomor siswa
            renderStudentNumbers();
            // Panggil renderObservers untuk memperbarui daftar observer
            renderObservers();
            resetSelections();
        });

        // Menambahkan event listener untuk menyimpan observer dan nomor siswa saat tombol simpanObserver diklik
        document.getElementById('simpanObserver').addEventListener('click', function(event) {
            // Simpan observer dan nomor siswa
            simpanObserver();
        });

        // Menambahkan event listener untuk menyembunyikan pop-up Tambah Observer saat batalPilihObserver diklik
        document.getElementById('batalPilihObserver').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default
            popupTambahObserver.classList.remove('popup-visible');
            popupTambahObserver.classList.add('popup-hidden');
            overlay.classList.remove('overlay-visible');
            overlay.classList.add('overlay-hidden');
            document.body.classList.remove('blur'); // Hapus efek blur dari latar belakang
            resetSelections(); // Reset pilihan
        });

        // Menambahkan event listener untuk menyembunyikan pop-up Tambah Observer saat klik di luar pop-up (overlay)
        overlay.addEventListener('click', function(event) {
            // Pastikan overlay hanya menutup pop-up jika pop-up Tambah Observer terlihat
            if (popupTambahObserver.classList.contains('popup-visible')) {
                popupTambahObserver.classList.remove('popup-visible');
                popupTambahObserver.classList.add('popup-hidden');
                overlay.classList.remove('overlay-visible');
                overlay.classList.add('overlay-hidden');
                document.body.classList.remove('blur'); // Hapus efek blur dari latar belakang
                resetSelections(); // Reset pilihan
            }
        });

        // Mencegah klik di dalam pop-up menutup pop-up
        popupTambahObserver.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        // Mendefinisikan elemen-elemen yang diperlukan
        const frameContainer = document.querySelector(".frame-container");
        const inputField = frameContainer.querySelector('input[name="cariObserver"]');
        const observerList = document.querySelector(".tambah-observer2");
        const observerGroup = document.querySelector(".item-observer-group");
        let noDataMessage;
        const chevronIcon = document.querySelector(".iconoutlinechevron-down");
        const observerIdInput = document.getElementById("observerId");
        const defaultPlaceholder = "Pilih Observer";

        const textButtonIcon = document.querySelector(".text-button-icon");
        const popupTambahObserverParent = document.querySelector(".pop-up-tambah-observer-parent");
        const simpanButton = document.getElementById("simpanObserver");
        const batalPilihObserver = document.getElementById("batalPilihObserver");
        const itemObserverContainer = document.querySelector(".item-observer-parent");
        const nomorSiswaIdInput = document.getElementById("nomorSiswaId");
        const nomorTidakTersediaInput = document.getElementById("nomorTidakTersedia");
        const textButton = document.querySelector(".text-button");
        const button2 = document.querySelector(".button2");
        const simpanKelasButton = document.getElementById("simpanKelas");

        let observerSelected = false;
        let selectedObserver = null;
        let selectedNomorSiswa = [];
        let tidakTersedia = new Set(); // Menggunakan Set untuk menghindari duplikasi
        let assignedObservers = []; // Array untuk menyimpan observer yang sudah dipilih

        // Mengonversi savedObservers menjadi savedObserversData dengan observerId sebagai integer
        let savedObserversData = savedObservers.map(observer => ({
            observerId: parseInt(observer.observerId), // Pastikan observerId adalah integer
            observerName: observer.observerName,
            nomorSiswa: observer.nomorSiswa,
            src_profile_photo: observer.src_profile_photo // Tambahkan src_profile_photo
        })); // Data observer yang disimpan di database

        // Jumlah siswa (bisa disesuaikan sesuai kebutuhan)
        const jumlahSiswa = 100;

        // Array status nomor siswa
        const statusNomorSiswa = Array(jumlahSiswa).fill("TERSEDIA");
        const predefinedTidakTersedia = [];

        // Menandai nomor siswa yang tidak tersedia
        predefinedTidakTersedia.forEach((num) => {
            statusNomorSiswa[num - 1] = "TIDAK TERSEDIA";
            tidakTersedia.add(num.toString());
        });

        // Inisialisasi status nomor siswa berdasarkan data yang disimpan di database
        savedObserversData.forEach(observer => {
            assignedObservers.push(parseInt(observer.observerId));
            observer.nomorSiswa.split(',').forEach(num => {
                const trimmedNum = num.trim();
                if (!tidakTersedia.has(trimmedNum)) { // Pastikan tidak ada duplikasi
                    statusNomorSiswa[trimmedNum - 1] = "TIDAK TERSEDIA";
                    tidakTersedia.add(trimmedNum);
                }
            });
        });

        /**
         * Fungsi untuk memperbarui status tombol simpan pada pop-up
         */
        function updateButtonState() {
            if (observerSelected && selectedNomorSiswa.length > 0) {
                simpanButton.disabled = false;
                simpanButton.classList.remove("disabled");
            } else {
                simpanButton.disabled = true;
                simpanButton.classList.add("disabled");
            }
            logNomorStatus();
        }

        // Membuat array observers dari usersData
        const observers = usersData.map(user => {
            return {
                id: parseInt(user.user_id),
                profileSrc: user.src_profile_photo ? "<?php echo base_url(); ?>" + user.src_profile_photo : '<?php echo base_url("assets/default/default_profile_picture.jpg"); ?>',
                name: user.full_name,
                email: user.email_address
            };
        });

        /**
         * Fungsi untuk menampilkan data observer ke dalam HTML
         */
        function renderObservers() {
            observerGroup.innerHTML = ''; // Bersihkan elemen sebelumnya

            // Tambahkan kode "Data Tidak Ditemukan" sebelum daftar observer
            const noDataDiv = document.createElement("div");
            noDataDiv.classList.add("data-tidak-ditemukan-parent");
            noDataDiv.innerHTML =
                `<div class="data-tidak-ditemukan">Data Tidak Ditemukan</div>`;
            observerGroup.appendChild(noDataDiv);
            noDataMessage = noDataDiv; // Update referensi

            observers.forEach(observer => {
                if (assignedObservers.includes(observer.id)) {
                    // Tandai observer yang sudah dipilih sebagai disabled
                    const observerHTML =
                        `<div class="item-observer4 observer-disabled" data-observer-id="${observer.id}">
                            <img oncontextmenu="return false;" class="profile-01-icon" alt="" src="${observer.profileSrc}">
                            <div class="nama-observer">${observer.name}</div>
                            <div class="observeremail">${observer.email}</div>
                        </div>`;
                    observerGroup.insertAdjacentHTML('beforeend', observerHTML);
                } else {
                    const observerHTML =
                        `<div class="item-observer4" data-observer-id="${observer.id}">
                            <img oncontextmenu="return false;" class="profile-01-icon" alt="" src="${observer.profileSrc}">
                            <div class="nama-observer">${observer.name}</div>
                            <div class="observeremail">${observer.email}</div>
                        </div>`;
                    observerGroup.insertAdjacentHTML('beforeend', observerHTML);
                }
            });
            addObserverEventListeners(); // Tambahkan event listener setelah render

            // Terapkan animasi scroll pada elemen nama-observer dan observeremail
            const namaObservers = document.querySelectorAll('.nama-observer');
            const observerEmails = document.querySelectorAll('.observeremail');
            namaObservers.forEach(element => {
                setupScrollAnimation(element);
            });
            observerEmails.forEach(element => {
                setupScrollAnimation(element);
            });
        }

        /**
         * Fungsi untuk menambahkan event listener pada observer items
         */
        function addObserverEventListeners() {
            const observerItems = document.querySelectorAll(".item-observer4:not(.observer-disabled)");
            observerItems.forEach((item) => {
                item.addEventListener("click", function() {
                    const observerName = item.querySelector(".nama-observer").textContent.trim();
                    const observerId = item.getAttribute("data-observer-id");
                    inputField.value = observerName;
                    inputField.readOnly = true;
                    inputField.style.pointerEvents = "none";
                    observerSelected = true;
                    observerIdInput.value = observerId;
                    observerList.style.display = "none";

                    // console.log(`Observer yang dipilih: ${observerName}, ID: ${observerId}`);
                    updateButtonState();
                });
            });
        }

        /**
         * Fungsi untuk log status nomor yang tersedia, dipilih, dan tidak tersedia
         */
        function logNomorStatus() {
            const tersedia = statusNomorSiswa
                .map((status, index) => (status === "TERSEDIA" ? index + 1 : null))
                .filter(Boolean); // Nomor siswa yang tersedia
            const dipilih = selectedNomorSiswa.map((num) => parseInt(num, 10)); // Nomor siswa yang dipilih
            const tidakTersediaList = Array.from(tidakTersedia).map((num) => parseInt(num, 10)); // Nomor siswa yang tidak tersedia
            // console.log(`Tersedia: ${tersedia.join(", ")}`);
            // console.log(`Dipilih: ${dipilih.join(", ")}`);
            // console.log(`Tidak Tersedia: ${tidakTersediaList.join(", ")}`);
        }

        /**
         * Fungsi untuk mereset semua pilihan observer dan nomor siswa
         */
        function resetSelections() {
            selectedObserver = null;
            observerSelected = false;
            selectedNomorSiswa = [];
            inputField.value = "";
            observerIdInput.value = "";
            nomorSiswaIdInput.value = "";
            inputField.readOnly = false;
            inputField.style.pointerEvents = "auto";
            inputField.placeholder = defaultPlaceholder;
            // Reset status nomor siswa dari "DIPILIH" ke "TERSEDIA"
            statusNomorSiswa.forEach((status, index) => {
                if (status === "DIPILIH") {
                    statusNomorSiswa[index] = "TERSEDIA";
                }
            });
            renderStudentNumbers(); // Perbarui tampilan
            updateButtonState();
        }

        /**
         * Event listener untuk "button2" untuk menutup pop-up observer
         */
        button2.addEventListener("click", function() {
            popupTambahObserver.classList.remove('popup-visible');
            popupTambahObserver.classList.add('popup-hidden');
            overlay.classList.remove('overlay-visible');
            overlay.classList.add('overlay-hidden');
            document.body.classList.remove('blur'); // Hapus efek blur dari latar belakang
            resetSelections(); // Reset pilihan
        });

        /**
         * Event listener untuk "textButton" untuk menutup pop-up observer
         */
        textButton.addEventListener("click", function() {
            popupTambahObserver.classList.remove('popup-visible');
            popupTambahObserver.classList.add('popup-hidden');
            overlay.classList.remove('overlay-visible');
            overlay.classList.add('overlay-hidden');
            document.body.classList.remove('blur'); // Hapus efek blur dari latar belakang
            resetSelections(); // Reset pilihan
        });

        // Pastikan tombol Tambah Observer berada di posisi paling bawah sebelum dan sesudah observer ditambahkan
        itemObserverContainer.appendChild(textButtonIcon);

        /**
         * Fungsi untuk merender nomor siswa yang bisa dipilih
         */
        function renderStudentNumbers() {
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

            // Loop untuk membuat daftar nomor siswa
            for (let i = 0; i < jumlahSiswa; i++) {
                const nomor = (i + 1).toString().padStart(2, "0");
                const siswaDiv = document.createElement("div");
                siswaDiv.setAttribute("data-nomor-id", i + 1);

                if (statusNomorSiswa[i] === "TERSEDIA") {
                    siswaDiv.classList.add("nomor-siswa-tersedia");
                    siswaDiv.innerHTML = `
                <div class="nomor-siswa-tersedia-item">
                    <div class="div">${nomor}</div>
                </div>`;
                } else if (statusNomorSiswa[i] === "DIPILIH") {
                    siswaDiv.classList.add("nomor-siswa-aktif");
                    siswaDiv.innerHTML = `
                <div class="nomor-siswa-aktif-item">
                    <div class="div">${nomor}</div>
                </div>`;
                } else if (statusNomorSiswa[i] === "TIDAK TERSEDIA") {
                    siswaDiv.classList.add("nomor-siswa-tidak-tersedia");
                    siswaDiv.innerHTML = `
                <div class="nomor-siswa-tidak-tersedia-item">
                    <div class="div">${nomor}</div>
                </div>
                <div class="frame-group">
                    <div class="siswa-diamati-observer-lain-wrapper">
                        <div class="siswa-diamati-observer-lain">Siswa diamati observer lain</div>
                    </div>
                    <img oncontextmenu="return false;" class="group-child3" alt="" src="assets/img/polygon.svg">
                </div>`;
                }

                // Tambahkan event listener untuk mengubah status nomor siswa saat diklik
                siswaDiv.addEventListener("click", function() {
                    const siswaId = this.getAttribute("data-nomor-id");
                    const index = parseInt(siswaId) - 1;

                    if (statusNomorSiswa[index] === "TERSEDIA") {
                        if (selectedNomorSiswa.length >= 12) {
                            showSweetAlert({
                                icon: 'warning',
                                title: 'Batas Tercapai',
                                text: 'Anda hanya dapat memilih maksimal 12 siswa untuk setiap observer.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#2563EB'
                            });
                            return;
                        }
                        statusNomorSiswa[index] = "DIPILIH";
                        this.classList.remove("nomor-siswa-tersedia");
                        this.classList.add("nomor-siswa-aktif");
                        this.innerHTML = `
                    <div class="nomor-siswa-aktif-item">
                        <div class="div">${nomor}</div>
                    </div>`;
                        selectedNomorSiswa.push(siswaId);
                    } else if (statusNomorSiswa[index] === "DIPILIH") {
                        statusNomorSiswa[index] = "TERSEDIA";
                        this.classList.remove("nomor-siswa-aktif");
                        this.classList.add("nomor-siswa-tersedia");
                        this.innerHTML = `
                    <div class="nomor-siswa-tersedia-item">
                        <div class="div">${nomor}</div>
                    </div>`;
                        const idx = selectedNomorSiswa.indexOf(siswaId);
                        if (idx !== -1) {
                            selectedNomorSiswa.splice(idx, 1);
                        }
                    }

                    nomorSiswaIdInput.value = selectedNomorSiswa.join(",");
                    infoPemilihan.textContent = `Siswa Dipilih: ${selectedNomorSiswa.length} / 12`; // Update informasi
                    updateButtonState();
                    // console.log("Selected Nomor Siswa IDs:", nomorSiswaIdInput.value);
                });

                // Tambahkan elemen siswa ke container
                pilihSiswaContainer.appendChild(siswaDiv);
            }
        }

        /**
         * Fungsi untuk log pilihan saat ini
         */
        function logSelections() {
            // console.log(`Observer terpilih: ${observerIdInput.value}`);
            // console.log(`Nomor siswa terpilih: ${nomorSiswaIdInput.value}`);
            // console.log(`Tidak Tersedia: ${Array.from(tidakTersedia).join(", ")}`);
        }

        /**
         * Event listener untuk fokus pada input saat frame-container diklik
         */
        frameContainer.addEventListener("click", function() {
            if (observerSelected) {
                inputField.value = "";
                observerSelected = false;
                inputField.readOnly = false;
                inputField.style.pointerEvents = "auto";
                inputField.placeholder = "Masukkan 3 atau lebih karakter";
                observerList.style.display = "block";
                chevronIcon.classList.add("rotated");
                resetHighlight();
                hideObserverItems();
                noDataMessage.style.display = "block";
            } else {
                inputField.focus();
            }
        });

        /**
         * Event listener untuk fokus pada input field
         */
        inputField.addEventListener("focus", function() {
            if (!observerSelected) {
                inputField.placeholder = "Masukkan 3 atau lebih karakter";
                observerList.style.display = "block";
                chevronIcon.classList.add("rotated");
            }
        });

        /**
         * Event listener untuk blur pada input field
         */
        inputField.addEventListener("blur", function() {
            if (!observerSelected && (inputField.value === "" || inputField.value.length < 3)) {
                inputField.value = ""; // Kosongkan input
                inputField.placeholder = defaultPlaceholder;
                noDataMessage.style.display = 'none'; // Sembunyikan pesan "Data Tidak Ditemukan"
            }
            setTimeout(function() {
                observerList.style.display = "none";
                chevronIcon.classList.remove("rotated");
            }, 200);
        });

        /**
         * Event listener untuk filter dan tampilkan hasil berdasarkan input
         */
        inputField.addEventListener("input", function() {
            const searchTerm = inputField.value.trim();

            if (searchTerm.length < 3) {
                hideObserverItems();
                noDataMessage.style.display = "block";
            } else {
                let matchFound = false;
                observers.forEach(observer => {
                    if (assignedObservers.includes(observer.id)) {
                        return; // Lewati observer yang sudah dipilih
                    }
                    const item = document.querySelector(`.item-observer4[data-observer-id="${observer.id}"]`);
                    if (!item) return; // Pastikan item ada
                    const observerName = observer.name.toLowerCase();
                    const observerEmail = observer.email.toLowerCase();
                    const searchTermLower = searchTerm.toLowerCase();

                    if (observerName.includes(searchTermLower) || observerEmail.includes(searchTermLower)) {
                        item.style.display = "flex";
                        highlightObserverItem(item, searchTerm);
                        matchFound = true;
                    } else {
                        item.style.display = "none";
                    }
                });

                noDataMessage.style.display = matchFound ? "none" : "block";
            }
        });

        /**
         * Fungsi untuk menyorot kata yang cocok pada nama dan email observer
         * @param {HTMLElement} item - Elemen observer yang akan disorot
         * @param {string} searchTerm - Term pencarian yang akan disorot
         */
        function highlightObserverItem(item, searchTerm) {
            const observerNameElement = item.querySelector(".nama-observer");
            const observerEmailElement = item.querySelector(".observeremail");
            const regExp = new RegExp(`(${searchTerm})`, "gi");

            observerNameElement.innerHTML = observerNameElement.textContent.replace(regExp, '<span class="bold">$1</span>');
            observerEmailElement.innerHTML = observerEmailElement.textContent.replace(regExp, '<span class="bold">$1</span>');

            // Setelah mengubah innerHTML, setup animasi scroll kembali
            setupScrollAnimation(observerNameElement);
            setupScrollAnimation(observerEmailElement);
        }

        /**
         * Fungsi untuk reset sorotan pada observer items
         */
        function resetHighlight() {
            const observerItems = document.querySelectorAll(".item-observer4");
            observerItems.forEach((item) => {
                const observerNameElement = item.querySelector(".nama-observer");
                const observerEmailElement = item.querySelector(".observeremail");
                observerNameElement.innerHTML = observerNameElement.textContent; // Reset ke teks asli tanpa highlight
                observerEmailElement.innerHTML = observerEmailElement.textContent; // Reset ke teks asli tanpa highlight

                // Setup animasi scroll kembali setelah reset
                setupScrollAnimation(observerNameElement);
                setupScrollAnimation(observerEmailElement);
            });
        }

        /**
         * Fungsi untuk menyembunyikan semua observer items
         */
        function hideObserverItems() {
            const observerItems = document.querySelectorAll(".item-observer4");
            observerItems.forEach((item) => {
                item.style.display = "none";
            });
        }

        /**
         * Fungsi untuk menyembunyikan overlay kustom
         */
        function hideCustomOverlay() {
            var overlay = document.getElementById('overlay');
            if (overlay.classList.contains('overlay-visible')) {
                overlay.classList.remove('overlay-visible');
                overlay.classList.add('overlay-hidden');
                return true; // Menandakan bahwa overlay kustom sebelumnya terlihat
            }
            return false; // Overlay kustom tidak terlihat sebelumnya
        }

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
         * Fungsi untuk menyimpan observer dan nomor siswa yang dipilih
         */
        function simpanObserver() {
            if (observerSelected && selectedNomorSiswa.length > 0) {
                if (selectedNomorSiswa.length > 12) {
                    showSweetAlert({
                        icon: 'error',
                        title: 'Jumlah Siswa Berlebih',
                        text: 'Anda hanya dapat memilih maksimal 12 siswa untuk setiap observer.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563EB'
                    });
                    return;
                }

                const observerName = inputField.value.trim();
                const observerId = observerIdInput.value;
                const observerObj = observers.find(o => o.id == observerId);
                const profileSrc = observerObj ? observerObj.profileSrc : "assets/default/default_profile_picture.jpg";
                const itemObserverHTML = `
                <div class="item-observer" data-observer-id="${observerId}">
                    <img oncontextmenu="return false;" class="ellipse-icon" alt="" src="${profileSrc}">
                    <div class="bagas-nugroho">${observerName}</div>
                    <div class="daftar-nomor-siswa">
                        ${selectedNomorSiswa
                        .map(
                            (num) => `
                                <div class="nomor-siswa">
                                    <div class="nomor-siswa-item">
                                        <div class="div">${num.padStart(2, "0")}</div>
                                    </div>
                                </div>`
                        )
                        .join("")}
                    </div>
                    <div class="download-button">
                        <div class="download-button-child"></div>
                        <img oncontextmenu="return false;" class="iconoutlinetrash" alt="" src="assets/img/trash_button.svg">
                        <div class="frame-parent">
                            <div class="unduh-wrapper">
                                <div class="unduh">Hapus</div>
                            </div>
                            <img oncontextmenu="return false;" class="polygon-icon" alt="" src="assets/img/polygon.svg">
                        </div>
                    </div>
                </div>`;

                // Menambahkan observer ke daftar observer yang sudah dipilih
                itemObserverContainer.insertBefore(
                    document.createRange().createContextualFragment(itemObserverHTML),
                    textButtonIcon
                );

                // Tambahkan nomor siswa ke Set tidakTersedia dan hidden input
                selectedNomorSiswa.forEach(num => tidakTersedia.add(num));
                nomorTidakTersediaInput.value = Array.from(tidakTersedia).join(",");

                // Update status nomor siswa menjadi "TIDAK TERSEDIA"
                selectedNomorSiswa.forEach(num => {
                    const index = parseInt(num) - 1;
                    statusNomorSiswa[index] = "TIDAK TERSEDIA";
                });

                // Hapus observer dari daftar observer yang tersedia
                assignedObservers.push(parseInt(observerId));
                renderObservers();

                // Simpan data observer bersama nomor siswa
                savedObserversData.push({
                    observerId: parseInt(observerId),
                    observerName: observerName,
                    nomorSiswa: selectedNomorSiswa.join(", "),
                    src_profile_photo: profileSrc // Tambahkan src_profile_photo
                });

                // console.log("Saved Observer Data:", savedObserversData);

                logSelections();
                resetSelections();

                // Pindahkan tombol Tambah Observer ke posisi paling bawah
                itemObserverContainer.appendChild(textButtonIcon);

                // Kirim data ke server untuk disimpan di sesi
                kirimDataKeSession();

                // Tutup pop-up setelah menyimpan
                popupTambahObserver.classList.remove('popup-visible');
                popupTambahObserver.classList.add('popup-hidden');
                overlay.classList.remove('overlay-visible');
                overlay.classList.add('overlay-hidden');
                document.body.classList.remove('blur'); // Hapus efek blur dari latar belakang

                // Aktifkan tombol simpanKelas karena form telah berubah
                isFormChanged = true;
                updateSimpanKelasButtonState(); // Perbarui status tombol
            }
        }

        /**
         * Event listener untuk menghapus observer dan memulihkan nomor siswa saat tombol hapus diklik
         */
        itemObserverContainer.addEventListener("click", function(event) {
            if (event.target.closest(".download-button")) {
                const observerItem = event.target.closest(".item-observer");
                const observerId = observerItem.getAttribute("data-observer-id");
                const selectedNomorSiswaItems = observerItem.querySelectorAll(".daftar-nomor-siswa .div");

                // Pulihkan nomor siswa ke "TERSEDIA"
                selectedNomorSiswaItems.forEach((item) => {
                    const nomor = item.textContent.trim();
                    const nomorIndex = parseInt(nomor) - 1;
                    statusNomorSiswa[nomorIndex] = "TERSEDIA";

                    // Hapus dari Set 'tidakTersedia'
                    tidakTersedia.delete(nomor);
                });

                // Hapus observer dari item-observer
                observerItem.remove();

                // Pulihkan observer ke daftar observer yang bisa dipilih
                const idxAssigned = assignedObservers.indexOf(parseInt(observerId));
                if (idxAssigned !== -1) {
                    assignedObservers.splice(idxAssigned, 1);
                }
                renderObservers();

                // Hapus data observer dari savedObserversData
                savedObserversData = savedObserversData.filter(data => data.observerId !== parseInt(observerId));
                // console.log("Saved Observer Data:", savedObserversData);

                // Perbarui hidden input nomorTidakTersedia
                nomorTidakTersediaInput.value = Array.from(tidakTersedia).join(",");

                // Pastikan tombol Tambah Observer berada di posisi paling bawah
                itemObserverContainer.appendChild(textButtonIcon);

                // Tandai form telah diubah
                isFormChanged = true;

                // Perbarui status tombol simpanKelas
                updateSimpanKelasButtonState();

                // Perbarui log nomor status setelah penghapusan
                logNomorStatus();
            }
        });

        /**
         * Event listener untuk tombol simpanKelas
         */
        simpanKelasButton.addEventListener("click", function(event) {
            event.preventDefault();
            // Set data observersData ke input hidden
            document.getElementById('observersData').value = JSON.stringify(savedObserversData);
            // Set isFormChanged menjadi false untuk mencegah alert default
            isFormChanged = false;
            // Submit form
            document.getElementById('formUpdateObserver').submit();
        });

        /**
         * Fungsi untuk mencegah pengguna meninggalkan halaman dengan perubahan yang belum disimpan
         */
        let isFormChanged = false; // Inisialisasi isFormChanged sebagai false

        /**
         * Fungsi untuk memperbarui status tombol simpanKelas berdasarkan kondisi form
         */
        function updateSimpanKelasButtonState() {
            // Cek apakah ada observer dalam form
            const observerItems = itemObserverContainer.querySelectorAll(".item-observer");
            if (observerItems.length === 0) {
                // Tidak ada observer, tombol simpanKelas di-disable
                simpanKelasButton.disabled = true;
                simpanKelasButton.classList.add("disabled");
            } else {
                // Ada observer
                if (isFormChanged) {
                    simpanKelasButton.disabled = false;
                    simpanKelasButton.classList.remove("disabled");
                } else {
                    simpanKelasButton.disabled = true;
                    simpanKelasButton.classList.add("disabled");
                }
            }
        }

        // Inisialisasi: Panggil fungsi untuk memperbarui status tombol saat halaman dimuat
        updateSimpanKelasButtonState();

        // Menggunakan MutationObserver untuk memantau perubahan pada itemObserverContainer
        const observerConfig = {
            childList: true,
            subtree: false
        };
        const mutationCallback = function(mutationsList, observer) {
            for (let mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    if (mutation.addedNodes.length > 0 || mutation.removedNodes.length > 0) {
                        if (mutation.target === itemObserverContainer) {
                            isFormChanged = true;
                            updateSimpanKelasButtonState(); // Perbarui status tombol
                        }
                    }
                }
            }
        };
        const mutationObserver = new MutationObserver(mutationCallback);
        mutationObserver.observe(itemObserverContainer, observerConfig);

        // Tambahkan event listener sebelum unload
        window.addEventListener('beforeunload', function(e) {
            if (isFormChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Event listener pada link untuk mencegah navigasi tanpa konfirmasi
        document.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (isFormChanged) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Perhatian',
                        text: 'Anda memiliki perubahan yang belum disimpan. Apakah Anda ingin menyimpan sebelum meninggalkan halaman ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        showDenyButton: true,
                        confirmButtonText: 'Simpan',
                        denyButtonText: 'Tinggalkan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#2563EB',
                        denyButtonColor: '#d33',
                        cancelButtonColor: '#a1a1aa'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Set data observersData ke input hidden
                            document.getElementById('observersData').value = JSON.stringify(savedObserversData);
                            // Set isFormChanged menjadi false dan submit form
                            isFormChanged = false;
                            document.getElementById('formUpdateObserver').submit();
                        } else if (result.isDenied) {
                            // Set isFormChanged menjadi false dan lanjutkan navigasi
                            isFormChanged = false;
                            window.location.href = link.href;
                        }
                    });
                }
            });
        });

        // Inisialisasi observer yang sudah disimpan saat halaman dimuat
        renderObservers();

        // Render nomor siswa saat halaman dimuat
        renderStudentNumbers();

        /**
         * Fungsi untuk menyimpan data ke sesi di server (AJAX Call)
         */
        function kirimDataKeSession() {
            // Mengirim data ke server menggunakan AJAX untuk disimpan di sesi
            $.ajax({
                url: '<?= site_url("simpanDataObserver"); ?>',
                type: 'POST',
                data: {
                    observersData: JSON.stringify(savedObserversData),
                    class_id: encrypted_idKelas
                },
                success: function(response) {
                    // console.log('Data berhasil disimpan di sesi:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Gagal menyimpan data di sesi:', error);
                }
            });
        }
    });

    window.onload = function() {
        // Daftar pasangan elemen target dan induknya (Kosong karena tidak digunakan saat ini)
        const targetPairs = [];

        // Daftar kelas atau atribut data yang akan dianimasikan secara langsung
        const directClassesToAnimate = [
            "nama-pengguna",
            "email-pengguna",
            "nama-observer",
            "observeremail",
            "bagas-nugroho",
            "item-observer4"
        ];

        // Gabungan semua elemen yang perlu dianimasikan
        const elementsToAnimate = [];

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
            });
        });

        // Menambahkan event listener untuk resize (opsional)
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
            });
        });
    };
</script>

</html>
