<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/buatkelas1.css" />
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

    <div class="buat-kelas unselectable-text">
        <div class="sidebar">
            <a class="logo-ls-1-parent link" href="sidebarBeranda">
                <img oncontextmenu="return false;" class="logo-ls-1" alt="" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </a>
            <a class="profile-side-bar link" href="sidebarProfile">
                <div class="profile-side-bar-child" alt="">
                    <img oncontextmenu="return false;" class="profile-photo" src="<?php echo !empty($user->src_profile_photo) ? $user->src_profile_photo : 'assets/default/default_profile_picture.jpg'; ?>" alt="">
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
        <a class="buat-kelas-group link" href="sidebarGuruModel">
            <img oncontextmenu="return false;" class="iconsolidarrow-left" alt="" src="assets/img/icon_arrow_left.svg">
            <div class="buat-kelas2">Buat Kelas
            </div>
        </a>
        <div class="date-container">
            <p id="dateDisplay" class="date"></p>
            <p id="timeDisplay" class="date"></p>
        </div>
        <div class="buat-kelas-parent">
            <div class="buat-kelas1">Buat Kelas</div>
            <div class="detail-kelas-parent">
                <div class="detail-kelas">
                    <ol class="detail-kelas1">
                        <li>1. Detail Kelas</li>
                    </ol>
                </div>
                <div class="unggah-berkas">
                    <ol class="detail-kelas1">
                        <li>2. Unggah Berkas</li>
                    </ol>
                </div>
                <div class="detail-observer">
                    <ol class="detail-kelas1">
                        <li>3. Detail Observer</li>
                    </ol>
                </div>
                <div class="group-child">
                </div>
                <div class="group-item">
                </div>
                <div class="group-inner">
                </div>
            </div>
            <div class="input-with-label-parent">
                <!-- Form untuk mengisi detail kelas -->
                <form enctype="multipart/form-data" action="formDetailKelas" method="POST">
                    <!-- Nama Kelas -->
                    <div class="input-field">
                        <div class="label">Nama Kelas</div>
                        <div class="input-field-inner">
                            <div class="placeholder-wrapper">
                                <input type="text" id="namaKelas" name="class_name" class="placeholder"
                                    placeholder="Masukkan Nama Kelas..."
                                    value="<?= isset($session_data['class_name']) ? htmlspecialchars($session_data['class_name'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <!-- Nama Sekolah -->
                    <div class="input-field1">
                        <div class="label">Nama Sekolah</div>
                        <div class="input-field-inner">
                            <div class="placeholder-wrapper">
                                <input type="text" id="namaSekolah" name="school_name" class="placeholder"
                                    placeholder="Masukkan Nama Sekolah..."
                                    value="<?= isset($session_data['school_name']) ? htmlspecialchars($session_data['school_name'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <!-- Mata Pelajaran -->
                    <div class="input-field2">
                        <div class="label">Mata Pelajaran</div>
                        <div class="input-field-inner">
                            <div class="placeholder-wrapper">
                                <input type="text" id="mataPelajaran" name="subject" class="placeholder"
                                    placeholder="Masukkan Mata Pelajaran..."
                                    value="<?= isset($session_data['subject']) ? htmlspecialchars($session_data['subject'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <!-- Kompetensi Dasar -->
                    <div class="text-area">
                        <div class="label">Kompetensi Dasar</div>
                        <div class="input-field-inner1">
                            <div class="placeholder-wrapper">
                                <div id="kompetensiDasar" name="basic_competency" class="placeholder1"
                                    contenteditable="true" placeholder="Masukkan Kompetensi Dasar"><?= isset($session_data['basic_competency']) ? htmlspecialchars($session_data['basic_competency'], ENT_QUOTES, 'UTF-8') : ''; ?></div>
                            </div>
                        </div>
                    </div>
                    <!-- Kontainer untuk Tanggal, Jam Mulai, dan Jam Selesai dalam satu baris -->
                    <div class="input-datetime-row" style="display: flex; gap: 20px;">
                        <!-- Tanggal -->
                        <div class="input-field3">
                            <div class="label">Tanggal</div>
                            <div class="input-field-inner">
                                <div class="placeholder-wrapper">
                                    <input type="text" id="tanggal" name="date" class="placeholder2"
                                        placeholder="DD/MM/YYYY"
                                        value="<?= isset($session_data['date']) ? htmlspecialchars(date('d/m/Y', strtotime($session_data['date'])), ENT_QUOTES, 'UTF-8') : ''; ?>">
                                </div>
                                <button id="buttonTanggal" type="button" class="icon-button">
                                    <img oncontextmenu="return false;" class="iconoutlinecalendar" alt="Calendar"
                                        src="assets/img/icon_calendar_form.svg">
                                </button>
                            </div>
                        </div>
                        <!-- Jam Mulai -->
                        <div class="input-field4">
                            <div class="label">Mulai</div>
                            <div class="input-field-inner">
                                <div class="placeholder-wrapper">
                                    <input type="text" id="jamMulai" name="start_time" class="placeholder3"
                                        placeholder="HH:MM"
                                        value="<?= isset($session_data['start_time']) ? htmlspecialchars(date('H:i', strtotime($session_data['start_time'])), ENT_QUOTES, 'UTF-8') : ''; ?>">
                                </div>
                                <button id="buttonJamMulai" type="button" class="icon-button">
                                    <img oncontextmenu="return false;" class="iconoutlinecalendar" alt="Clock" src="assets/img/icon_clock_form.svg">
                                </button>
                            </div>
                        </div>
                        <!-- Jam Selesai -->
                        <div class="input-field5">
                            <div class="label">Selesai</div>
                            <div class="input-field-inner">
                                <div class="placeholder-wrapper">
                                    <input type="text" id="jamSelesai" name="end_time" class="placeholder3"
                                        placeholder="HH:MM"
                                        value="<?= isset($session_data['end_time']) ? htmlspecialchars(date('H:i', strtotime($session_data['end_time'])), ENT_QUOTES, 'UTF-8') : ''; ?>">
                                </div>
                                <button id="buttonJamSelesai" type="button" class="icon-button">
                                    <img oncontextmenu="return false;" class="iconoutlinecalendar" alt="Clock" src="assets/img/icon_clock_form.svg">
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Tombol Selanjutnya -->
                    <button type="submit" id="Selanjutnya" class="button link">
                        <div class="button1">Selanjutnya</div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
    // Menunggu seluruh konten DOM dimuat sebelum menjalankan skrip
    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen flashdata
        var flashdata = document.getElementById('flashdata');
        var success = flashdata.getAttribute('data-success');
        var error = flashdata.getAttribute('data-error');

        // Menampilkan SweetAlert untuk pesan sukses jika ada
        if (success) {
            Swal.fire({
                icon: 'success', // Jenis ikon
                title: 'Berhasil', // Judul pesan
                text: success, // Isi pesan
                confirmButtonText: 'OK', // Teks tombol konfirmasi
                confirmButtonColor: '#2563EB' // Warna tombol konfirmasi
            });
        }

        // Menampilkan SweetAlert untuk pesan error jika ada
        if (error) {
            Swal.fire({
                icon: 'error', // Jenis ikon
                title: 'Error', // Judul pesan
                text: error, // Isi pesan
                confirmButtonText: 'OK', // Teks tombol konfirmasi
                confirmButtonColor: '#2563EB' // Warna tombol konfirmasi
            });
        }
    });

    // Fungsi untuk memperbarui tanggal dan waktu saat ini
    function updateDateTime() {
        const now = new Date(); // Mendapatkan waktu saat ini
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

        // Mengupdate elemen HTML dengan tanggal dan waktu yang telah diformat
        document.getElementById('dateDisplay').innerText = dateString;
        document.getElementById('timeDisplay').innerText = timeString;
    }

    // Memanggil fungsi updateDateTime setiap setengah detik untuk memperbarui waktu secara real-time
    setInterval(updateDateTime, 500);

    // Memastikan waktu saat ini ditampilkan saat memuat halaman
    updateDateTime();

    // Menjalankan kode setelah DOM selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen input dan tombol terkait
        const dateInput = document.getElementById('tanggal');
        const startTimeInput = document.getElementById('jamMulai');
        const endTimeInput = document.getElementById('jamSelesai');
        const nextButton = document.getElementById('Selanjutnya');
        const dateButton = document.getElementById('buttonTanggal');
        const startTimeButton = document.getElementById('buttonJamMulai');
        const endTimeButton = document.getElementById('buttonJamSelesai');

        // Objek yang menyimpan semua input untuk memudahkan pengelolaan
        const inputs = {
            class_name: document.getElementById('namaKelas'),
            school_name: document.getElementById('namaSekolah'),
            subject: document.getElementById('mataPelajaran'),
            basic_competency: document.getElementById('kompetensiDasar'),
            date: dateInput,
            start_time: startTimeInput,
            end_time: endTimeInput
        };

        // Menambahkan fokus pada input namaKelas saat halaman dibuka
        inputs.class_name.focus();

        /**
         * Fungsi untuk memeriksa apakah semua input sudah terisi
         */
        function checkAllInputs() {
            let allFilled = true;
            for (let key in inputs) {
                const input = inputs[key];
                if (input) {
                    // Memeriksa apakah input kosong
                    if ((key === "basic_competency" && input.innerText.trim() === "") || (input.value !== undefined && input.value.trim() === "")) {
                        allFilled = false;
                        break;
                    }
                } else {
                    allFilled = false;
                    break;
                }
            }
            // Mengaktifkan atau menonaktifkan tombol selanjutnya berdasarkan status pengisian
            nextButton.disabled = !allFilled;
            if (allFilled) {
                // console.log("Semua input sudah terisi.");
            } else {
                // console.log("Masih ada input yang kosong.");
            }
        }

        /**
         * Fungsi untuk mencatat input yang sudah terisi
         * @param {string} inputName - Nama input yang diisi
         */
        function logInputFilled(inputName) {
            if (!logInputFilled.loggedInputs) {
                logInputFilled.loggedInputs = {};
            }
            if (!logInputFilled.loggedInputs[inputName]) {
                // console.log(`${inputName} sudah terisi.`);
                logInputFilled.loggedInputs[inputName] = true;
            }
        }

        /**
         * Fungsi untuk memformat dan memperbaiki input tanggal
         * @param {Event} event - Event input
         */
        dateInput.addEventListener('input', function(event) {
            let value = dateInput.value.replace(/[^\d\/]/g, ''); // Menghapus karakter selain digit dan '/'
            if (value.length > 2 && value[2] !== '/') {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            if (value.length > 5 && value[5] !== '/') {
                value = value.slice(0, 5) + '/' + value.slice(5);
            }
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            dateInput.value = value;

            // Jika panjang nilai sudah mencapai format DD/MM/YYYY, perbaiki tanggal
            if (value.length === 10) {
                const day = Math.min(Math.max(1, parseInt(value.slice(0, 2))), 31);
                const month = Math.min(Math.max(1, parseInt(value.slice(3, 5))), 12);
                const year = parseInt(value.slice(6, 10));
                const correctedDate = new Date(year, month - 1, day);
                dateInput.value = formatDate(correctedDate);
            }

            logInputFilled('Tanggal'); // Mencatat bahwa input tanggal sudah diisi
            checkAllInputs(); // Memeriksa apakah semua input sudah terisi
        });

        // Menambahkan event listener untuk menghapus input tanggal jika format tidak lengkap saat kehilangan fokus
        dateInput.addEventListener('blur', function() {
            if (dateInput.value.length < 10) {
                dateInput.value = '';
            } else {
                const parts = dateInput.value.split('/');
                if (parts.length !== 3 || parts[0].length !== 2 || parts[1].length !== 2 || parts[2].length !== 4) {
                    dateInput.value = '';
                }
            }
            checkAllInputs();
        });

        /**
         * Fungsi untuk memformat dan memperbaiki input waktu
         * @param {HTMLElement} timeInput - Elemen input waktu
         * @param {string} inputName - Nama input waktu
         */
        function handleTimeInput(timeInput, inputName) {
            timeInput.addEventListener('input', function(event) {
                let value = timeInput.value.replace(/[^\d:]/g, ''); // Menghapus karakter selain digit dan ':'
                if (value.length > 2 && value[2] !== ':') {
                    value = value.slice(0, 2) + ':' + value.slice(2);
                }
                if (value.length > 5) {
                    value = value.slice(0, 5);
                }
                timeInput.value = value;

                // Jika panjang nilai sudah mencapai format HH:MM, perbaiki waktu
                if (value.length === 5) {
                    const hours = Math.min(Math.max(0, parseInt(value.slice(0, 2))), 23);
                    const minutes = Math.min(Math.max(0, parseInt(value.slice(3, 5))), 59);
                    timeInput.value = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
                    adjustEndTime(); // Menyesuaikan waktu selesai
                }

                logInputFilled(inputName); // Mencatat bahwa input waktu sudah diisi
                checkAllInputs(); // Memeriksa apakah semua input sudah terisi
            });

            // Menambahkan event listener untuk menghapus input waktu jika format tidak lengkap saat kehilangan fokus
            timeInput.addEventListener('blur', function() {
                if (timeInput.value.length < 5) {
                    timeInput.value = '';
                } else {
                    const parts = timeInput.value.split(':');
                    if (parts.length !== 2 || parts[0].length !== 2 || parts[1].length !== 2) {
                        timeInput.value = '';
                    }
                }
                checkAllInputs();
            });
        }

        // Memanggil fungsi handleTimeInput untuk jamMulai dan jamSelesai
        handleTimeInput(startTimeInput, 'Mulai');
        handleTimeInput(endTimeInput, 'Selesai');

        /**
         * Fungsi untuk memastikan jamSelesai selalu 1 menit lebih besar dari jamMulai
         */
        function adjustEndTime() {
            if (startTimeInput.value && endTimeInput.value) {
                const startTime = parseTime(startTimeInput.value);
                const endTime = parseTime(endTimeInput.value);
                if (!startTime || !endTime) return;
                if (endTime <= startTime) {
                    const adjustedEndTime = new Date(startTime.getTime() + 60000); // Menambahkan 1 menit
                    endTimeInput.value = formatTime(adjustedEndTime);
                }
            }
        }

        /**
         * Fungsi untuk parsing tanggal dari input
         * @param {string} input - String tanggal dalam format DD/MM/YYYY
         * @returns {Date|null} - Objek Date atau null jika parsing gagal
         */
        function parseDate(input) {
            const parts = input.split('/');
            if (parts.length !== 3) return null;
            let day = parseInt(parts[0], 10);
            let month = parseInt(parts[1], 10) - 1;
            let year = parseInt(parts[2], 10);
            if (isNaN(day) || isNaN(month) || isNaN(year)) return null;
            return new Date(year, month, day);
        }

        /**
         * Fungsi untuk parsing waktu dari input
         * @param {string} input - String waktu dalam format HH:MM
         * @returns {Date|null} - Objek Date dengan waktu diatur atau null jika parsing gagal
         */
        function parseTime(input) {
            const parts = input.split(':');
            if (parts.length !== 2) return null;
            let hours = parseInt(parts[0], 10);
            let minutes = parseInt(parts[1], 10);
            if (isNaN(hours) || isNaN(minutes)) return null;
            let date = new Date();
            date.setHours(hours, minutes, 0, 0);
            return date;
        }

        /**
         * Fungsi untuk memformat tanggal ke format DD/MM/YYYY
         * @param {Date} date - Objek Date yang akan diformat
         * @returns {string} - String tanggal dalam format DD/MM/YYYY
         */
        function formatDate(date) {
            const day = ('0' + date.getDate()).slice(-2);
            const month = ('0' + (date.getMonth() + 1)).slice(-2);
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        /**
         * Fungsi untuk memformat waktu ke format HH:MM
         * @param {Date} date - Objek Date yang akan diformat
         * @returns {string} - String waktu dalam format HH:MM
         */
        function formatTime(date) {
            const hours = ('0' + date.getHours()).slice(-2);
            const minutes = ('0' + date.getMinutes()).slice(-2);
            return `${hours}:${minutes}`;
        }

        // Menangani input pada basic_competency
        const basicCompetencyDiv = document.getElementById('kompetensiDasar');

        basicCompetencyDiv.addEventListener('input', function() {
            logInputFilled('Kompetensi Dasar'); // Mencatat bahwa input kompetensi dasar sudah diisi
            checkAllInputs(); // Memeriksa apakah semua input sudah terisi
        });

        // Menangani input pada class_name, school_name, subject
        inputs.class_name.addEventListener('input', function() {
            logInputFilled('Nama Kelas'); // Mencatat bahwa input nama kelas sudah diisi
            checkAllInputs(); // Memeriksa apakah semua input sudah terisi
        });

        inputs.school_name.addEventListener('input', function() {
            logInputFilled('Nama Sekolah'); // Mencatat bahwa input nama sekolah sudah diisi
            checkAllInputs(); // Memeriksa apakah semua input sudah terisi
        });

        inputs.subject.addEventListener('input', function() {
            logInputFilled('Mata Pelajaran'); // Mencatat bahwa input mata pelajaran sudah diisi
            checkAllInputs(); // Memeriksa apakah semua input sudah terisi
        });

        // Menangani submit form untuk mengirim nilai basic_competency
        const form = document.querySelector('form');

        form.addEventListener('submit', function() {
            // Menambahkan input tersembunyi untuk mengirim data kompetensiDasar
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'basic_competency';
            hiddenInput.value = basicCompetencyDiv.innerText.trim();
            form.appendChild(hiddenInput);
        });

        // Memeriksa input saat halaman pertama kali dimuat
        checkAllInputs();

        // Menambahkan event listener pada tombol ikon untuk fokus pada input terkait
        dateButton.addEventListener('click', function() {
            dateInput.focus();
        });

        startTimeButton.addEventListener('click', function() {
            startTimeInput.focus();
        });

        endTimeButton.addEventListener('click', function() {
            endTimeInput.focus();
        });

        // === Penambahan Kode untuk Mengatur Fokus Antar Input Tanggal, Jam Mulai, dan Jam Selesai ===

        // Mendapatkan elemen input tanggal, jam mulai, dan jam selesai
        const tanggalInput = document.getElementById('tanggal');
        const jamMulaiInput = document.getElementById('jamMulai');
        const jamSelesaiInput = document.getElementById('jamSelesai');

        // Ketika tanggal diisi penuh, fokus ke jam mulai
        tanggalInput.addEventListener('input', function() {
            if (tanggalInput.value.length === 10) { // Format DD/MM/YYYY
                jamMulaiInput.focus();
            }
        });

        // Ketika jam mulai diisi penuh, fokus ke jam selesai
        jamMulaiInput.addEventListener('input', function() {
            if (jamMulaiInput.value.length === 5) { // Format HH:MM
                jamSelesaiInput.focus();
            }
        });

        // Ketika jam selesai dihapus dan ingin dihapus lagi, fokus kembali ke jam mulai
        jamSelesaiInput.addEventListener('keydown', function(event) {
            if (event.key === 'Backspace' && jamSelesaiInput.value.length === 0) {
                event.preventDefault();
                jamMulaiInput.focus();
            }
        });

        // Ketika jam mulai dihapus dan ingin dihapus lagi, fokus kembali ke tanggal
        jamMulaiInput.addEventListener('keydown', function(event) {
            if (event.key === 'Backspace' && jamMulaiInput.value.length === 0) {
                event.preventDefault();
                tanggalInput.focus();
            }
        });

        // === Akhir Penambahan Kode ===

    });

    // Fungsi yang dijalankan saat window selesai dimuat
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
                // console.log(`Tidak perlu animasi untuk elemen:`, element);
                // console.log(`Alasan: scrollWidth (${overflowInfo.scrollWidth}px) - clientWidth (${overflowInfo.clientWidth}px) = ${overflowInfo.difference}px (Tidak melebihi toleransi 1px)`);
                return;
            } else {
                // console.log(`Animasi diperlukan untuk elemen:`, element);
                // console.log(`Alasan: scrollWidth (${overflowInfo.scrollWidth}px) - clientWidth (${overflowInfo.clientWidth}px) = ${overflowInfo.difference}px (Melebihi toleransi 1px)`);
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
                let animationId = null; // ID animasi yang sedang berjalan

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
         * Fungsi ini memastikan bahwa animasi tetap konsisten saat ukuran window berubah
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