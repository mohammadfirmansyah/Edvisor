<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/gurumodel.css" />
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
        data-success="<?= isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-error="<?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <div class="guru-model unselectable-text">
        <!-- Sidebar navigasi -->
        <div class="sidebar">
            <!-- Logo Lesson Study yang mengarahkan ke halaman Beranda -->
            <a class="logo-ls-1-parent link" href="sidebarBeranda">
                <img oncontextmenu="return false;" class="logo-ls-1" alt="" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </a>
            <!-- Profil pengguna yang mengarahkan ke halaman Profile -->
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
            <!-- Tombol keluar/logout -->
            <a class="item-side-bar-exit link" href="sidebarLogout">
                <img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_keluar.svg">
                <div class="text-sidebar-default">Keluar</div>
            </a>
        </div>
        <!-- Judul halaman Guru Model -->
        <div class="guru-model1">Guru Model</div>
        <!-- Kontainer untuk menampilkan tanggal dan waktu -->
        <div class="date-container">
            <p id="dateDisplay" class="date"></p>
            <p id="timeDisplay" class="date"></p>
        </div>
        <!-- Frame utama yang berisi daftar kelas dan tabel -->
        <div class="frame-parent">
            <!-- Bagian daftar kelas saya -->
            <div class="daftar-kelas-saya-parent">
                <div class="daftar-kelas-saya">Daftar Kelas Saya</div>
                <div class="frame-group">
                    <!-- Fitur pencarian kelas -->
                    <div class="search-parent">
                        <img oncontextmenu="return false;" class="notifications-active-icon1" alt="Icon Search" src="assets/img/icon_search.svg">
                        <div class="cari-disini">
                            <div class="search-parent">
                                <input type="text" name="search" class="search-input" placeholder="Cari Disini....." />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tombol untuk membuat kelas baru -->
                <a class="button link" href="pageBuatKelas">
                    <img oncontextmenu="return false;" class="iconoutlinehome" alt="" src="assets/img/icon_add.svg">
                    <div class="button1">Buat Kelas</div>
                </a>
            </div>
            <!-- Kontainer tabel kelas -->
            <div class="frame-container">
                <!-- Header Kolom Tabel -->
                <div class="nama-kelas-parent">
                    <div class="judul-nama-kelas unselectable-text" data-column="data-nama-kelas">Nama Kelas</div>
                    <div class="judul-mata-pelajaran unselectable-text" data-column="data-mata-pelajaran">Mata Pelajaran</div>
                    <div class="judul-observer unselectable-text">Observer</div>
                    <div class="judul-tanggal unselectable-text" data-column="tanggal-aktivitas-saya">Tanggal</div>
                    <div class="judul-status unselectable-text" data-column="isi-status">Status</div>
                </div>

                <!-- Kontainer Tabel Kelas -->
                <div class="frame-container1" id="table-container">
                    <?php if (!empty($classes)) : ?>
                        <?php foreach ($classes as $class): ?>
                            <!-- Baris tabel untuk setiap kelas -->
                            <a class="item-tabel link" href="<?php echo site_url('pageKelasGuruModel/' . $class->encrypted_class_id); ?>">
                                <!-- Nama Kelas -->
                                <div class="data-nama-kelas"><?php echo htmlspecialchars($class->class_name); ?></div>

                                <!-- Mata Pelajaran -->
                                <div class="data-mata-pelajaran"><?php echo htmlspecialchars($class->subject); ?></div>

                                <!-- Observer Terbaru -->
                                <div class="profile-groupvariant9">
                                    <?php if (!empty($class->latest_observers)): ?>
                                        <?php foreach ($class->latest_observers as $observer): ?>
                                            <img oncontextmenu="return false;" class="profile-groupvariant9-item" alt="<?php echo htmlspecialchars($observer->full_name); ?>" src="<?php echo base_url($observer->src_profile_photo); ?>">
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Jika tidak ada observer, tampilkan placeholder atau kosong -->
                                        <img oncontextmenu="return false;" class="profile-groupvariant9-item" alt="No Observer" src="assets/default/default_profile_picture.jpg">
                                    <?php endif; ?>
                                </div>

                                <!-- Tanggal Aktivitas -->
                                <div class="tanggal-aktivitas-saya"><?php echo htmlspecialchars(indonesian_date($class->date), ENT_QUOTES, 'UTF-8'); ?></div>

                                <!-- Status Kelas -->
                                <?php
                                // Menentukan kelas CSS berdasarkan status kelas
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
                                    <div class="isi-status"><?php echo htmlspecialchars($class->status); ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Pesan jika tidak ada kelas yang tersedia -->
                        <div class="empty_activity">Buat kelas untuk observer Anda sekarang.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

<script>   
    // Event yang dijalankan setelah seluruh konten DOM dimuat
    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen flashdata
        var flashdata = document.getElementById('flashdata');
        var success = flashdata.getAttribute('data-success');
        var error = flashdata.getAttribute('data-error');

        // Menampilkan SweetAlert untuk pesan sukses
        if (success) {
            Swal.fire({
                icon: 'success', // Jenis ikon
                title: 'Berhasil', // Judul alert
                text: success, // Isi pesan
                confirmButtonText: 'OK', // Teks tombol konfirmasi
                confirmButtonColor: '#2563EB' // Warna tombol konfirmasi
            });
        }

        // Menampilkan SweetAlert untuk pesan error
        if (error) {
            Swal.fire({
                icon: 'error', // Jenis ikon
                title: 'Error', // Judul alert
                text: error, // Isi pesan
                confirmButtonText: 'OK', // Teks tombol konfirmasi
                confirmButtonColor: '#2563EB' // Warna tombol konfirmasi
            });
        }
    });

    /**
     * Fungsi untuk memperbarui tampilan tanggal dan waktu secara real-time
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

        // Mengupdate elemen HTML dengan ID 'dateDisplay' dan 'timeDisplay'
        document.getElementById('dateDisplay').innerText = dateString;
        document.getElementById('timeDisplay').innerText = timeString;
    }

    // Memanggil fungsi updateDateTime setiap 500 milidetik untuk memperbarui waktu
    setInterval(updateDateTime, 500);

    // Memastikan waktu saat ini ditampilkan saat memuat halaman
    updateDateTime();

    // Event listener untuk pencarian kelas
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('.search-input'); // Input pencarian
        const tabelLinks = document.querySelectorAll('.item-tabel'); // Semua baris tabel kelas

        /**
         * Event 'input' yang dijalankan setiap kali pengguna mengetik di input pencarian
         */
        searchInput.addEventListener('input', function() {
            const searchQuery = searchInput.value.toLowerCase(); // Mengubah query pencarian menjadi huruf kecil

            tabelLinks.forEach(link => {
                // Mendapatkan teks dari setiap kolom yang relevan
                const namaKelas = link.querySelector('.data-nama-kelas').textContent.toLowerCase();
                const mataPelajaran = link.querySelector('.data-mata-pelajaran').textContent.toLowerCase();
                const tanggal = link.querySelector('.tanggal-aktivitas-saya').textContent.toLowerCase();
                const status = link.querySelector('.isi-status').textContent.toLowerCase();

                // Menampilkan baris jika query pencarian cocok dengan salah satu kolom
                if (namaKelas.includes(searchQuery) || mataPelajaran.includes(searchQuery) ||
                    tanggal.includes(searchQuery) || status.includes(searchQuery)) {
                    link.style.display = '';
                } else {
                    link.style.display = 'none';
                }
            });
        });
    });

    // Event listener untuk pengurutan tabel
    document.addEventListener("DOMContentLoaded", function() {
        const tableContainer = document.getElementById("table-container"); // Kontainer tabel
        const headers = document.querySelectorAll(".nama-kelas-parent > div[data-column]"); // Header kolom yang bisa diurutkan
        let currentSort = {
            column: '', // Kolom yang sedang diurutkan
            order: 'asc', // Urutan: 'asc' atau 'desc'
            clicks: {} // Menghitung jumlah klik per kolom
        };
        const originalOrder = Array.from(tableContainer.getElementsByClassName("item-tabel")); // Menyimpan urutan asli tabel

        // Menambahkan event listener pada setiap header kolom
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const column = header.getAttribute('data-column'); // Mendapatkan nama kolom

                // Mengabaikan pengurutan pada kolom 'guru model'
                if (column === 'data-guru-model') {
                    return;
                }

                handleSort(column, header); // Memanggil fungsi pengurutan
            });
        });

        /**
         * Fungsi untuk menangani pengurutan tabel berdasarkan kolom yang dipilih
         * @param {string} column - Nama kolom yang akan diurutkan
         * @param {HTMLElement} header - Elemen header kolom yang diklik
         */
        function handleSort(column, header) {
            const items = Array.from(tableContainer.getElementsByClassName("item-tabel")); // Mendapatkan semua baris tabel

            if (!currentSort.clicks[column]) {
                currentSort.clicks[column] = 0; // Inisialisasi jumlah klik jika belum ada
            }
            currentSort.clicks[column]++;

            if (currentSort.column === column) {
                if (column === 'isi-status') {
                    // Pengurutan khusus untuk kolom 'isi-status'
                    if (currentSort.clicks[column] === 1) {
                        currentSort.statusStep = 1;
                    } else if (currentSort.clicks[column] === 2) {
                        currentSort.statusStep = 2;
                    } else if (currentSort.clicks[column] === 3) {
                        currentSort.statusStep = 3;
                    } else {
                        currentSort.clicks[column] = 0;
                        currentSort.statusStep = 0;
                        resetTable(); // Mengembalikan tabel ke urutan asli
                        return;
                    }
                } else {
                    // Pengurutan untuk kolom lain
                    if (currentSort.clicks[column] === 1) {
                        currentSort.order = 'asc';
                    } else if (currentSort.clicks[column] === 2) {
                        currentSort.order = 'desc';
                    } else {
                        currentSort.order = 'default';
                        currentSort.clicks[column] = 0;
                        resetTable(); // Mengembalikan tabel ke urutan asli
                        return;
                    }
                }
            } else {
                // Jika kolom yang diklik berbeda dengan kolom yang sedang diurutkan
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
                // Definisi prioritas status berdasarkan jumlah klik
                let priority = [];
                if (currentSort.statusStep === 1) {
                    priority = ['Akan Datang', 'Belum Dilengkapi', 'Selesai'];
                } else if (currentSort.statusStep === 2) {
                    priority = ['Belum Dilengkapi', 'Akan Datang', 'Selesai'];
                } else if (currentSort.statusStep === 3) {
                    priority = ['Selesai', 'Akan Datang', 'Belum Dilengkapi'];
                }

                // Mengurutkan item berdasarkan prioritas status
                items.sort((a, b) => {
                    const aStatus = a.getElementsByClassName('isi-status')[0].innerText.trim();
                    const bStatus = b.getElementsByClassName('isi-status')[0].innerText.trim();

                    return priority.indexOf(aStatus) - priority.indexOf(bStatus);
                });
            } else {
                // Mengurutkan item berdasarkan teks di kolom yang dipilih
                items.sort((a, b) => {
                    const aText = a.getElementsByClassName(column)[0].innerText.trim();
                    const bText = b.getElementsByClassName(column)[0].innerText.trim();

                    if (column === 'tanggal-aktivitas-saya') {
                        // Pengurutan khusus untuk tanggal
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

            // Menghapus konten tabel saat ini
            while (tableContainer.firstChild) {
                tableContainer.removeChild(tableContainer.firstChild);
            }

            // Menambahkan kembali item yang telah diurutkan
            items.forEach(item => tableContainer.appendChild(item));
            updateHeaders(header); // Memperbarui tampilan header kolom
        }

        /**
         * Fungsi untuk mengubah string tanggal menjadi objek Date
         * @param {string} dateString - String tanggal dalam format 'DD MMMM YYYY'
         * @returns {Date} - Objek Date
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
            const [day, month, year] = dateString.split(' ');
            return new Date(year, monthMap[month], parseInt(day));
        }

        /**
         * Fungsi untuk memperbarui tampilan header kolom setelah diurutkan
         * @param {HTMLElement} activeHeader - Header kolom yang sedang diurutkan
         */
        function updateHeaders(activeHeader) {
            // Menghapus tanda panah pada semua header
            headers.forEach(header => header.innerText = header.innerText.replace(/[\u2191\u2193]/g, ''));
            if (currentSort.column === 'isi-status' && currentSort.clicks['isi-status'] > 0) {
                activeHeader.innerText += ' \u2193'; // Menambahkan panah turun
            } else if (currentSort.order !== 'default') {
                activeHeader.innerText += currentSort.order === 'asc' ? ' \u2191' : ' \u2193'; // Menambahkan panah sesuai urutan
            }
        }

        /**
         * Fungsi untuk mengembalikan tabel ke urutan asli
         */
        function resetTable() {
            // Menghapus semua konten tabel
            while (tableContainer.firstChild) {
                tableContainer.removeChild(tableContainer.firstChild);
            }

            // Menambahkan kembali item dalam urutan asli
            originalOrder.forEach(item => tableContainer.appendChild(item));
            // Menghapus tanda panah pada semua header
            headers.forEach(header => header.innerText = header.innerText.replace(/[\u2191\u2193]/g, ''));
        }
    });


    // Event listener tambahan untuk mengatur fokus pada input pencarian
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('.search-input'); // Input pencarian

        /**
         * Fungsi untuk mengatur fokus pada input pencarian setelah delay
         */
        function setFocus() {
            setTimeout(() => {
                searchInput.focus(); // Mengatur fokus pada input pencarian
            }, 300); // Tambahkan delay 300ms
        }

        setFocus(); // Memanggil fungsi setFocus
    });

    // Fungsi yang dijalankan saat seluruh halaman telah dimuat
    window.onload = function() {
        // Daftar pasangan elemen target dan induknya (kosong karena tidak ada pasangan yang didefinisikan)
        const targetPairs = [];

        // Daftar kelas atau atribut data yang akan dianimasikan secara langsung
        const directClassesToAnimate = [
            "data-nama-kelas",
            "data-mata-pelajaran",
            "tanggal-aktivitas-saya",
            "nama-pengguna",
            "email-pengguna"
        ];

        // Gabungan semua elemen yang perlu dianimasikan
        const elementsToAnimate = [];

        /**
         * Fungsi untuk memeriksa apakah elemen mengalami overflow
         * @param {HTMLElement} element - Elemen yang akan diperiksa
         * @returns {Object} - Informasi tentang overflow
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
                // Jika elemen tidak overflow, tidak perlu animasi
                // console.log(`Tidak perlu animasi untuk elemen:`, element);
                // console.log(`Alasan: scrollWidth (${overflowInfo.scrollWidth}px) - clientWidth (${overflowInfo.clientWidth}px) = ${overflowInfo.difference}px (Tidak melebihi toleransi 1px)`);
                return;
            } else {
                // Jika elemen overflow, perlu animasi
                // console.log(`Animasi diperlukan untuk elemen:`, element);
                // console.log(`Alasan: scrollWidth (${overflowInfo.scrollWidth}px) - clientWidth (${overflowInfo.clientWidth}px) = ${overflowInfo.difference}px (Melebihi toleransi 1px)`);
            }

            // Menyimpan teks asli dalam data attribute untuk pemulihan nanti
            const originalText = element.textContent.trim();
            element.setAttribute('data-original-text', originalText);

            // Menerapkan gaya default dengan ellipsis
            element.style.overflow = "hidden";
            element.style.textOverflow = "ellipsis";
            element.style.whiteSpace = "nowrap";

            /**
             * Fungsi untuk memulai animasi scroll
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
                let animationId = null; // ID animasi

                /**
                 * Fungsi animasi menggunakan requestAnimationFrame
                 * @param {DOMHighResTimeStamp} timestamp - Waktu saat ini
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

                // Memulai animasi scroll
                animationId = requestAnimationFrame(animate);

                // Menyimpan ID animasi dalam data attribute untuk referensi nanti
                element.setAttribute('data-animation-id', animationId);
            }

            /**
             * Fungsi untuk menghentikan animasi scroll dan mengembalikan posisi awal
             */
            function stopScroll() {
                if (element.getAttribute('data-animating') !== 'true') return;

                const animationId = element.getAttribute('data-animation-id');
                if (animationId) {
                    cancelAnimationFrame(animationId); // Membatalkan animasi
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

        // Mengolah pasangan target dengan induknya (tidak ada pasangan yang didefinisikan)
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
            // Menggunakan selector kelas
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
         * Event listener untuk resize window yang memastikan animasi tetap sesuai
         */
        window.addEventListener('resize', function() {
            elementsToAnimate.forEach(item => {
                const {
                    element
                } = item;

                // Membatalkan animasi jika sedang berjalan
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

                // Memeriksa kembali overflow dan menerapkan animasi jika perlu
                setupScrollAnimation(element);

                const overflowInfo = isElementOverflowing(element);
                if (overflowInfo.overflowing) {
                    if (item.type === 'parent') {
                        // Menambahkan event listener pada induk jika tipe 'parent'
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
                        // Menambahkan event listener langsung pada elemen jika tipe 'direct'
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