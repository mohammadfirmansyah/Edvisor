<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <base href="<?php echo base_url(); ?>">
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/kelasobserver2.css" />
</head>

<body>
    <!-- Pesan Flashdata -->
    <?php
    // Mendapatkan pesan flashdata dari session
    $success_message = $this->session->flashdata('success');
    $error_message = $this->session->flashdata('error');
    ?>
    <!-- Menyimpan pesan flashdata dalam elemen tersembunyi -->
    <div id="flashdata"
        data-success="<?= isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-error="<?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <div class="detail-penilaian-4 unselectable-text">
        <!-- Sidebar Navigasi -->
        <div class="sidebar">
            <!-- Logo dan Navigasi Utama -->
            <a class="logo-ls-1-parent link" href="sidebarBeranda">
                <img oncontextmenu="return false;" class="logo-ls-1" alt="Logo Lesson Study" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </a>
            <!-- Profil Pengguna -->
            <a class="profile-side-bar link" href="sidebarProfile">
                <div class="profile-side-bar-child" alt="Foto Profil">
                    <img oncontextmenu="return false;" class="profile-photo"
                        src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>"
                        alt="Foto Profil">
                </div>
                <!-- Menampilkan Nama Pengguna dengan Pengecekan -->
                <div class="nama-pengguna">
                    <?= isset($user->full_name) && !empty($user->full_name) ? htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8') : 'Nama Tidak Tersedia'; ?>
                </div>
                <!-- Menampilkan Email Pengguna dengan Pengecekan -->
                <div class="email-pengguna">
                    <?= isset($user->email_address) && !empty($user->email_address) ? htmlspecialchars($user->email_address, ENT_QUOTES, 'UTF-8') : 'Email Tidak Tersedia'; ?>
                </div>
            </a>
            <!-- Menu Navigasi -->
            <div class="menu-bar">
                <a class="item-side-bar-default link" href="sidebarBeranda">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Beranda" src="assets/img/icon_beranda.svg">
                    <div class="text-sidebar-default">Beranda</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarGuruModel">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Guru Model" src="assets/img/icon_guru_model.svg">
                    <div class="text-sidebar-default">Guru Model</div>
                </a>
                <a class="item-side-bar-active link" href="sidebarObserver">
                    <img oncontextmenu="return false;" class="icon-sidebar-active" alt="Observer" src="assets/img/icon_observer.svg">
                    <div class="text-sidebar-active">Observer</div>
                </a>
                <a class="item-side-bar-default link" href="sidebarBantuan">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Bantuan" src="assets/img/icon_bantuan.svg">
                    <div class="text-sidebar-default">Bantuan</div>
                </a>
            </div>
            <!-- Tombol Keluar -->
            <a class="item-side-bar-exit link" href="sidebarLogout">
                <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Keluar" src="assets/img/icon_keluar.svg">
                <div class="text-sidebar-default">Keluar</div>
            </a>
        </div>
        <!-- Navigasi Detail Kelas -->
        <a class="detail-kelas-group link" href="sidebarObserver">
            <img oncontextmenu="return false;" class="iconsolidarrow-left" alt="Kembali" src="assets/img/icon_arrow_left.svg">
            <div class="detail-kelas1">Observasi</div>
        </a>
        <!-- Display Tanggal dan Waktu -->
        <div class="date-container">
            <p id="dateDisplay" class="date"></p>
            <p id="timeDisplay" class="date"></p>
        </div>
        <div class="frame-parent">
            <!-- Header Kelas -->
            <div class="card-header">
                <div class="xi-rekayasa-perangkat"><?= htmlspecialchars($class->class_name, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="dasar-pemrograman">
                    <span class="dasar-pemrograman1"><?= htmlspecialchars($class->subject, ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="dasar-pemrograman2"> | <?= htmlspecialchars($class->number_of_students, ENT_QUOTES, 'UTF-8'); ?> Siswa</span>
                </div>
                <div class="kompetensi-dasar-menerapkan-container">
                    <span class="kompetensi-dasar">Kompetensi Dasar: </span>
                    <b><?= htmlspecialchars($class->basic_competency, ENT_QUOTES, 'UTF-8'); ?></b>
                    <span> </span>
                </div>
                <div class="group-parent">
                    <div class="group-div">
                        <div class="date-range-parent">
                            <img oncontextmenu="return false;" class="iconoutlineinformation-circl" alt="Tanggal" src="assets/img/icon_calendar.svg">
                            <div class="nomor-siswa"><?= htmlspecialchars(indonesian_date($class->date), ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    </div>
                    <div class="alarm-parent">
                        <img oncontextmenu="return false;" class="iconoutlineinformation-circl" alt="Waktu" src="assets/img/icon_alarm.svg">
                        <div class="nomor-siswa"><?= date('H:i', strtotime($class->start_time)) . ' - ' . date('H:i', strtotime($class->end_time)); ?></div>
                    </div>
                </div>
                <!-- Informasi Guru Model -->
                <div class="guru-model-parent">
                    <div class="guru-model">Guru Model : </div>
                    <img oncontextmenu="return false;" class="group-child"
                        src="<?php echo !empty($class->guru_model_src_profile_photo) ? base_url($class->guru_model_src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>"
                        alt="Foto Guru Model">
                    <div class="courtney-henry"><?= htmlspecialchars($class->guru_model_name, ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <!-- Informasi Observer -->
                <div class="observer-parent">
                    <div class="observer">Observer &emsp;:</div>
                    <div class="group-wrapper">
                        <?php
                        // Mendapatkan 4 Observer terbaru untuk kelas ini
                        $latestObservers = $this->ClassObserver->getLatestObservers($class->class_id, 4);
                        if (!empty($latestObservers)) {
                            foreach ($latestObservers as $observer) {
                                // Menampilkan foto Observer dengan pengecekan nama
                                echo '<img oncontextmenu="return false;" class="group-icon" src="' .
                                    (!empty($observer->src_profile_photo) ? base_url($observer->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg')) .
                                    '" alt="' .
                                    (isset($observer->full_name) && !empty($observer->full_name) ? htmlspecialchars($observer->full_name, ENT_QUOTES, 'UTF-8') : 'Observer') .
                                    '">';
                            }
                        } else {
                            // Jika tidak ada Observer, tampilkan teks
                            echo '<span>Tidak ada Observer</span>';
                        }
                        ?>
                    </div>
                </div>
                <!-- Berkas yang Dibutuhkan -->
                <div class="berkas-yang-dibutuhkan-parent">
                    <div class="berkas-yang-dibutuhkan">Berkas Yang Dibutuhkan</div>
                    <div class="frame-child1"></div>
                    <!-- Data Siswa -->
                    <img oncontextmenu="return false;" class="iconsoliddocument-text" alt="Data Siswa" src="assets/img/icon_doc.svg">
                    <div class="text-button">
                        <div class="ini-adalah-text">Data Siswa</div>
                    </div>
                    <!-- Tombol Download Data Siswa dengan Ekstensi Asli -->
                    <div class="download-button" data-file="<?= !empty($class->src_student_data_file) ? base_url($class->src_student_data_file) : ''; ?>" data-filename="Data_Siswa.<?= !empty($class->student_file_ext) ? htmlspecialchars($class->student_file_ext, ENT_QUOTES, 'UTF-8') : 'csv'; ?>">
                        <div class="download-button-child"></div>
                        <img oncontextmenu="return false;" class="iconsoliddownload" alt="Download Data Siswa" src="assets/img/icon_download.svg">
                        <div class="frame-parent1">
                            <div class="unduh-wrapper">
                                <div class="unduh">Unduh</div>
                            </div>
                            <img oncontextmenu="return false;" class="group-item" alt="" src="assets/img/polygon.svg">
                        </div>
                    </div>
                    <!-- Modul Ajar -->
                    <img oncontextmenu="return false;" class="iconsoliddocument-text1" alt="Modul Ajar" src="assets/img/icon_doc.svg">
                    <div class="text-button1">
                        <div class="ini-adalah-text">Modul Ajar</div>
                    </div>
                    <!-- Tombol Download Modul Ajar dengan Ekstensi Asli -->
                    <div class="download-button1" data-file="<?= !empty($class->src_teaching_module_file) ? base_url($class->src_teaching_module_file) : ''; ?>" data-filename="Modul_Ajar.<?= !empty($class->modul_file_ext) ? htmlspecialchars($class->modul_file_ext, ENT_QUOTES, 'UTF-8') : 'pdf'; ?>">
                        <div class="download-button-child"></div>
                        <img oncontextmenu="return false;" class="iconsoliddownload" alt="Download Modul Ajar" src="assets/img/icon_download.svg">
                        <div class="frame-parent1">
                            <div class="unduh-wrapper">
                                <div class="unduh">Unduh</div>
                            </div>
                            <img oncontextmenu="return false;" class="group-item" alt="" src="assets/img/polygon.svg">
                        </div>
                    </div>
                    <!-- Media Pembelajaran -->
                    <img oncontextmenu="return false;" class="iconsoliddocument-text2" alt="Media Pembelajaran" src="assets/img/icon_doc.svg">
                    <div class="text-button2">
                        <div class="ini-adalah-text">Media Pembelajaran</div>
                    </div>
                    <!-- Tombol Download Media Pembelajaran dengan Ekstensi Asli -->
                    <div class="download-button2" data-file="<?= !empty($class->src_learning_media_file) ? base_url($class->src_learning_media_file) : ''; ?>" data-filename="Media_Pembelajaran.<?= !empty($class->media_file_ext) ? htmlspecialchars($class->media_file_ext, ENT_QUOTES, 'UTF-8') : 'pdf'; ?>">
                        <div class="download-button-child"></div>
                        <img oncontextmenu="return false;" class="iconsoliddownload" alt="Download Media Pembelajaran" src="assets/img/icon_download.svg">
                        <div class="frame-parent1">
                            <div class="unduh-wrapper">
                                <div class="unduh">Unduh</div>
                            </div>
                            <img oncontextmenu="return false;" class="group-item" alt="" src="assets/img/polygon.svg">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Menu Tab -->
            <div class="tab-menu">
                <a class="menu-2 link link-color-unset" href="pageKelasObserver1/<?php echo $encrypted_class_id; ?>">
                    <div class="penilaian-kegiatan-mengajar">Penilaian Kegiatan Mengajar</div>
                </a>
                <div class="menu-1">
                    <div class="penilaian-kegiatan-mengajar">Lembar Pengamatan Siswa
                    </div>
                </div>
                <a class="menu-2 link link-color-unset" href="pageKelasObserver3/<?php echo $encrypted_class_id; ?>">
                    <div class="penilaian-kegiatan-mengajar">Catatan Aktivitas Siswa</div>
                </a>
            </div>
            <!-- Form Pengamatan Siswa -->
            <div class="frame-group">
                <!-- Petunjuk Pengisian Formulir -->
                <div class="petunjuk-parent">
                    <div class="petunjuk">
                        <div class="iconoutlineinformation-circl-parent">
                            <img oncontextmenu="return false;" class="iconoutlineinformation-circl" alt="Informasi" src="assets/img/icon_information.svg">
                            <div class="petunjuk1">Petunjuk</div>
                            <img oncontextmenu="return false;" class="iconoutlineinformation-circl1" alt="Tutup" src="assets/img/icon_up.svg">
                        </div>
                        <div class="berilah-skor-pada-indikatoras-wrapper">
                            <div class="berilah-skor-pada">Berilah tanda check (✓) apabila siswa MEMPERLIHATKAN perilaku/gejala sesuai indikator, atau biarkan kosong apabila siswa TIDAK MEMPERLIHATKAN perilaku/gejala sesuai indikator.</div>
                        </div>
                    </div>
                    <!-- Header Formulir -->
                    <div class="frame-wrapper">
                        <div class="indikator-parent">
                            <div class="indikator">Indikator</div>
                            <div class="nomor-siswa-parent">
                                <b class="nomor-siswa">Nomor Siswa</b>
                                <div class="number-scroll-wrapper">
                                    <!-- Tombol Shift Kiri -->
                                    <button id="shiftLeft" class="shift-button">&lt;</button>
                                    <!-- Container Scroll Nomor Siswa -->
                                    <div class="number-scroll-container">
                                        <div class="number-scroll-wrapper">
                                            <div class="student-number-list">
                                                <?php
                                                // Mendapatkan daftar nomor siswa yang diamati oleh observer
                                                $student_numbers = $this->ObservedStudent->getStudentNumbersByObserver($class->class_id, $this->session->userdata('user_id'));

                                                // Mengurutkan nomor siswa dari terkecil hingga terbesar
                                                sort($student_numbers, SORT_NUMERIC);

                                                // Memastikan minimal 5 nomor siswa
                                                $min_display = 5;
                                                $current_count = count($student_numbers);

                                                // Jika kurang dari 5, tambahkan nomor kosong
                                                if ($current_count < $min_display) {
                                                    for ($i = 0; $i < ($min_display - $current_count); $i++) {
                                                        $student_numbers[] = '';
                                                    }
                                                }

                                                // Batasi hingga 100 nomor siswa untuk mencegah beban berlebih
                                                $student_numbers = array_slice($student_numbers, 0, 100);

                                                foreach ($student_numbers as $student_number) {
                                                    echo '<div class="student-number">' . htmlspecialchars($student_number, ENT_QUOTES, 'UTF-8') . '</div>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tombol Shift Kanan -->
                                    <button id="shiftRight" class="shift-button">&gt;</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Formulir Pengamatan -->
                <form id="observationForm" action="formLembarPengamatanSiswa" method="POST">
                    <!-- Menyertakan ID Kelas dan ID Observer sebagai input tersembunyi -->
                    <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($encrypted_class_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="observer_user_id" value="<?php echo htmlspecialchars($this->session->userdata('user_id'), ENT_QUOTES, 'UTF-8'); ?>">
                    <!-- Menambahkan Input Tersembunyi untuk Menandai Perubahan Tanda Tangan -->
                    <input type="hidden" name="signatureChanged" id="signatureChanged" value="0">
                    <!-- Daftar Indikator dan Checkbox -->
                    <div class="frame-container">
                        <?php
                        // Daftar indikator
                        $indicators = [
                            'Menerima (Receiving)' => [
                                'Menanyakan materi yang kurang atau tidak dipahami',
                                'Memperhatikan materi yang dijelaskan guru',
                                'Mengikuti proses pembelajaran dengan tertib',
                                'Memberi informasi yang jujur terkait materi yang dipelajari'
                            ],
                            'Menanggapi (Responding)' => [
                                'Menjawab pertanyaan guru atau teman tentang materi yang dipelajari',
                                'Membantu mengatasi kesulitan teman dalam memahami materi',
                                'Berdiskusi dengan guru atau teman terkait materi yang dipelajari',
                                'Menulis hasil diskusi/penjelasan materi/tugas dari guru'
                            ],
                            'Menilai (Valuing)' => [
                                'Mampu menjelaskan materi kepada teman sekelas',
                                'Berinisiatif mengungkapkan pendapat kepada teman atau guru',
                                'Melaporkan tugas pelajaran secara sistematis',
                                'Melaksanakan pembelajaran secara seksama dan aktif'
                            ],
                            'Mengorganisasi (Organizing)' => [
                                'Mampu mengorganisasi diri sendiri untuk aktif belajar',
                                'Mampu bekerja sama dalam kelompok pada saat diskusi',
                                'Mampu merancang perhitungan/menganalisis materi sebelum dipraktikkan',
                                'Mengusulkan pendapat dalam proses pembelajaran',
                                'Mempraktikkan kedisiplinan dalam belajar',
                                'Menunjukkan rasa ingin tahu yang tinggi dalam belajar'
                            ]
                        ];

                        $indicator_number = 1;
                        foreach ($indicators as $category => $items) {
                            echo '<div class="menerima-receiving-wrapper">';
                            echo '<b class="menerima-receiving">' . htmlspecialchars($category, ENT_QUOTES, 'UTF-8') . '</b>';
                            echo '</div>';

                            foreach ($items as $item) {
                                echo '<div class="menanyakan-materi-yang-kurang-parent">';
                                echo '<div class="menanyakan-materi-yang">' . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . '</div>';
                                echo '<div class="check-all">';
                                echo '<div class="checkbox-parent">';
                                echo '<div class="checkbox-scroll-container">';
                                echo '<div class="checkbox-list">';

                                foreach ($student_numbers as $student_number) {
                                    // Lewati checkbox jika nomor siswa kosong
                                    if (empty($student_number)) {
                                        continue;
                                    }

                                    // Cek apakah sudah ada nilai yang disimpan sebelumnya
                                    $checked = isset($observation_details[$indicator_number][$student_number]) && $observation_details[$indicator_number][$student_number];

                                    if ($checked) {
                                        // Jika sudah dicentang, tampilkan ikon checked
                                        echo '<img oncontextmenu="return false;" class="checkbox-icon" alt="Checked" src="assets/img/icon_check.svg" data-indicator="' . $indicator_number . '" data-student="' . htmlspecialchars($student_number, ENT_QUOTES, 'UTF-8') . '" data-value="1">';
                                    } else {
                                        // Jika belum dicentang, tampilkan checkbox kosong
                                        echo '<div class="checkbox" data-indicator="' . $indicator_number . '" data-student="' . htmlspecialchars($student_number, ENT_QUOTES, 'UTF-8') . '" data-value="0">';
                                        echo '<div class="large-ico-check"></div>';
                                        echo '</div>';
                                    }
                                }

                                echo '</div>'; // Tutup checkbox-list
                                echo '</div>'; // Tutup checkbox-scroll-container
                                echo '</div>'; // Tutup checkbox-parent
                                echo '<div class="checkbox-group">';
                                echo '<div class="checkbox select-all" data-indicator="' . $indicator_number . '">';
                                echo '<div class="large-ico-check"></div>';
                                echo '</div>';
                                echo '<div class="pilih-semua">Pilih Semua</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';

                                $indicator_number++;
                            }
                            echo '<div class="frame-child"></div>';
                        }
                        ?>
                    </div>
                    <!-- Catatan -->
                    <div class="text-area">
                        <div class="label">Secara umum, pengalaman berharga apa yang dapat Anda peroleh dari kegiatan pengamatan terhadap siswa-siswa tersebut dalam kegiatan pembelajaran ini?</div>
                        <div class="text-area-child">
                            <textarea name="notes" id="notes"><?= isset($observation_sheet) ? htmlspecialchars($observation_sheet->notes, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                        </div>
                    </div>
                    <!-- Tanda Tangan -->
                    <div class="tanda-tangan">
                        <div class="tanda-tangan1">Tanda Tangan</div>
                        <div class="line-parent">
                            <canvas id="signatureCanvas" width="500" height="500"></canvas>
                            <input id="signatureImage" type="hidden" name="src_signature_file" value="<?= isset($observation_sheet) && isset($observation_sheet->src_signature_file) ? htmlspecialchars($observation_sheet->src_signature_file, ENT_QUOTES, 'UTF-8') : ''; ?>">
                            <div id="placeholderCanvas" class="buat-tanda-tangan">Buat Tanda Tangan Di Sini</div>
                            <img oncontextmenu="return false;" id="deleteSignature" class="delete-button-icon"
                                alt="Hapus Tanda Tangan" src="<?= isset($observation_sheet) && !empty($observation_sheet->src_signature_file) ? 'assets/img/icon_delete_active.svg' : 'assets/img/icon_delete_inactive.svg'; ?>">
                        </div>
                        <button type="submit" class="button link" id="saveForm">
                            <div class="button1">Simpan</div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<script> 
    // Menampilkan pesan flashdata (sukses atau error) menggunakan SweetAlert2
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

    // Fungsi untuk memperbarui tanggal dan waktu secara real-time
    /**
     * Fungsi ini memperbarui elemen HTML dengan ID 'dateDisplay' dan 'timeDisplay'
     * untuk menampilkan tanggal dan waktu saat ini dalam format bahasa Indonesia.
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

    // Menangani fokus dan hover pada textarea
    document.addEventListener('DOMContentLoaded', function() {
        let isHovering = false;

        // Dapatkan semua elemen textarea
        const textareas = document.querySelectorAll('textarea');

        textareas.forEach(function(textarea) {
            // Saat textarea mendapat fokus, tambahkan outline kecuali sedang hover
            textarea.addEventListener('focus', function() {
                if (!isHovering) {
                    this.closest('.text-area-child').style.outline = '2px solid #6366F1';
                }
            });

            // Saat textarea kehilangan fokus, hilangkan outline
            textarea.addEventListener('blur', function() {
                this.closest('.text-area-child').style.outline = 'none';
            });

            // Saat mouse di atas textarea, sembunyikan outline dan set flag hover
            textarea.addEventListener('mouseover', function() {
                isHovering = true;
                this.closest('.text-area-child').style.outline = 'none';
            });

            // Saat mouse keluar dari textarea, kembalikan outline jika dalam keadaan fokus
            textarea.addEventListener('mouseout', function() {
                isHovering = false;
                if (this === document.activeElement) {
                    this.closest('.text-area-child').style.outline = '2px solid #6366F1';
                }
            });
        });
    });

    // Fungsi untuk menangani tanda tangan
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signatureCanvas');
        const signatureImage = document.getElementById('signatureImage');
        const deleteSignatureButton = document.getElementById('deleteSignature');
        const placeholderCanvas = document.getElementById('placeholderCanvas');
        const context = canvas.getContext('2d');
        let isDrawing = false;
        let hasSignature = false;
        let drawingEnabled = true;
        let signatureChanged = false;

        /**
         * Fungsi untuk mengatur ukuran canvas sesuai dengan CSS dan DPR (Device Pixel Ratio).
         */
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            context.scale(dpr, dpr);
            context.lineCap = 'round';
            context.lineJoin = 'round';
            context.strokeStyle = '#000';
            context.lineWidth = 2;

            // Jika sudah ada tanda tangan, gambar kembali dari src_signature_file
            if (signatureImage.value) {
                const img = new Image();
                img.src = signatureImage.value;
                img.onload = function() {
                    // Hitung skala agar gambar sesuai dengan canvas
                    const scale = Math.min(canvas.width / (window.devicePixelRatio || 1) / img.width, canvas.height / (window.devicePixelRatio || 1) / img.height);
                    const scaledWidth = img.width * scale;
                    const scaledHeight = img.height * scale;
                    const x = ((canvas.width / (window.devicePixelRatio || 1)) - scaledWidth) / 2;
                    const y = ((canvas.height / (window.devicePixelRatio || 1)) - scaledHeight) / 2;
                    context.drawImage(img, x, y, scaledWidth, scaledHeight);
                    hasSignature = true;
                    updateDeleteButtonState();
                    hidePlaceholder();
                    disableCanvas();
                };
            } else {
                hasSignature = false;
                updateDeleteButtonState();
                showPlaceholder();
            }
        }

        // Panggil resizeCanvas saat halaman dimuat
        resizeCanvas();

        // Pastikan canvas tetap responsif saat ukuran jendela berubah
        window.addEventListener('resize', function() {
            // Simpan data saat ini
            const dataUrl = canvas.toDataURL();
            // Resize canvas
            resizeCanvas();
            // Restore data
            const img = new Image();
            img.onload = function() {
                context.clearRect(0, 0, canvas.width, canvas.height);
                context.drawImage(img, 0, 0, canvas.width / (window.devicePixelRatio || 1), canvas.height / (window.devicePixelRatio || 1));
            };
            img.src = dataUrl;
        });

        /**
         * Fungsi untuk memulai menggambar.
         * @param {MouseEvent} event - Event mouse saat menggambar.
         */
        function startDrawing(event) {
            if (!drawingEnabled) return;
            isDrawing = true;
            signatureChanged = true;
            document.getElementById('signatureChanged').value = "1";
            const {
                x,
                y
            } = getMousePos(canvas, event);
            context.beginPath();
            context.moveTo(x, y);
        }

        /**
         * Fungsi untuk menggambar di canvas.
         * @param {MouseEvent} event - Event mouse saat menggambar.
         */
        function draw(event) {
            if (!isDrawing || !drawingEnabled) return;
            const {
                x,
                y
            } = getMousePos(canvas, event);
            context.lineTo(x, y);
            context.stroke();
            hasSignature = true;
            updateSignatureImage();
            updateDeleteButtonState();
            hidePlaceholder();
        }

        /**
         * Fungsi untuk berhenti menggambar.
         */
        function stopDrawing() {
            if (isDrawing) {
                isDrawing = false;
                updateSignatureImage();
            }
        }

        /**
         * Fungsi untuk memperbarui gambar tanda tangan ke dalam input (PNG).
         */
        async function updateSignatureImage() {
            if (!isCanvasBlank()) {
                const processedDataUrl = await processSignature();
                if (processedDataUrl) {
                    signatureImage.value = processedDataUrl;
                    signatureChanged = true;
                    document.getElementById('signatureChanged').value = "1";
                    hidePlaceholder();
                } else {
                    signatureImage.value = '';
                    showPlaceholder();
                }
            } else {
                signatureImage.value = '';
                showPlaceholder();
            }
            updateDeleteButtonState();
        }

        /**
         * Fungsi untuk memproses tanda tangan: cropping dan scaling.
         * @returns {Promise<string>} Data URL dari tanda tangan yang diproses.
         */
        function processSignature() {
            return new Promise((resolve) => {
                // Mengambil data dari canvas asli
                const dataUrl = canvas.toDataURL('image/png');
                const img = new Image();
                img.src = dataUrl;

                img.onload = function() {
                    // Membuat canvas untuk cropping
                    const tempCanvas = document.createElement('canvas');
                    const tempContext = tempCanvas.getContext('2d');

                    tempCanvas.width = img.width;
                    tempCanvas.height = img.height;
                    tempContext.drawImage(img, 0, 0);

                    // Mendapatkan data pixel dari tempCanvas
                    const imageData = tempContext.getImageData(0, 0, tempCanvas.width, tempCanvas.height);
                    const pixels = imageData.data;

                    let minX = tempCanvas.width,
                        minY = tempCanvas.height,
                        maxX = 0,
                        maxY = 0;

                    // Menemukan batas tanda tangan
                    for (let y = 0; y < tempCanvas.height; y++) {
                        for (let x = 0; x < tempCanvas.width; x++) {
                            const index = (y * tempCanvas.width + x) * 4;
                            const alpha = pixels[index + 3];
                            if (alpha > 0) { // Menemukan piksel yang tidak transparan
                                if (x < minX) minX = x;
                                if (x > maxX) maxX = x;
                                if (y < minY) minY = y;
                                if (y > maxY) maxY = y;
                            }
                        }
                    }

                    // Jika tidak ada tanda tangan, kembalikan canvas kosong
                    if (maxX < minX || maxY < minY) {
                        resolve('');
                        return;
                    }

                    const croppedWidth = maxX - minX;
                    const croppedHeight = maxY - minY;

                    // Membuat canvas untuk tanda tangan yang di-crop
                    const croppedCanvas = document.createElement('canvas');
                    const croppedContext = croppedCanvas.getContext('2d');
                    croppedCanvas.width = croppedWidth;
                    croppedCanvas.height = croppedHeight;
                    croppedContext.drawImage(tempCanvas, minX, minY, croppedWidth, croppedHeight, 0, 0, croppedWidth, croppedHeight);

                    // Membuat canvas final berukuran 500x500
                    const finalCanvas = document.createElement('canvas');
                    const finalContext = finalCanvas.getContext('2d');
                    finalCanvas.width = 500;
                    finalCanvas.height = 500;

                    // Menghitung skala agar tanda tangan memenuhi secara horizontal dan vertikal
                    const scale = Math.min(finalCanvas.width / croppedWidth, finalCanvas.height / croppedHeight);
                    const scaledWidth = croppedWidth * scale;
                    const scaledHeight = croppedHeight * scale;

                    // Menggambar tanda tangan yang di-crop dan di-scale di tengah canvas final
                    const offsetX = (finalCanvas.width - scaledWidth) / 2;
                    const offsetY = (finalCanvas.height - scaledHeight) / 2;
                    finalContext.drawImage(croppedCanvas, 0, 0, croppedWidth, croppedHeight, offsetX, offsetY, scaledWidth, scaledHeight);

                    // Mendapatkan data URL dari finalCanvas
                    resolve(finalCanvas.toDataURL('image/png'));
                };

                img.onerror = function() {
                    resolve('');
                };
            });
        }

        /**
         * Cek apakah canvas kosong.
         * @returns {boolean} True jika canvas kosong, false jika tidak.
         */
        function isCanvasBlank() {
            const blankCanvas = document.createElement('canvas');
            blankCanvas.width = canvas.width;
            blankCanvas.height = canvas.height;
            const blankContext = blankCanvas.getContext('2d');
            // Jangan isi dengan warna apa pun agar blankCanvas transparan
            return canvas.toDataURL() === blankCanvas.toDataURL();
        }

        /**
         * Fungsi untuk menyembunyikan placeholder.
         */
        function hidePlaceholder() {
            if (!isCanvasBlank()) {
                placeholderCanvas.style.display = 'none';
            }
        }

        /**
         * Fungsi untuk menampilkan placeholder.
         */
        function showPlaceholder() {
            if (isCanvasBlank()) {
                placeholderCanvas.style.display = 'block';
            }
        }

        /**
         * Perbarui status tombol delete.
         */
        function updateDeleteButtonState() {
            if (hasSignature && signatureImage.value) {
                deleteSignatureButton.src = 'assets/img/icon_delete_active.svg';
                deleteSignatureButton.style.cursor = 'pointer'; // Mengubah cursor menjadi pointer
            } else {
                deleteSignatureButton.src = 'assets/img/icon_delete_inactive.svg';
                deleteSignatureButton.style.cursor = 'not-allowed'; // Mengubah cursor menjadi not-allowed
            }
        }

        /**
         * Fungsi untuk menghapus tanda tangan.
         */
        function clearCanvas() {
            if (!hasSignature) return; // Jika belum ada tanda tangan, tidak perlu melakukan apa-apa

            // Menampilkan SweetAlert2 untuk konfirmasi
            Swal.fire({
                title: 'Hapus Tanda Tangan',
                text: 'Apakah Anda yakin ingin menghapus tanda tangan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    hasSignature = false;
                    signatureImage.value = '';
                    signatureChanged = true; // Menandai tanda tangan telah diubah
                    document.getElementById('signatureChanged').value = "1"; // Mengatur nilai input tersembunyi
                    updateDeleteButtonState();
                    showPlaceholder();
                    enableCanvas(); // Mengaktifkan kembali kanvas setelah menghapus tanda tangan
                }
            });
        }

        /**
         * Fungsi untuk mendapatkan posisi mouse relatif terhadap canvas.
         * @param {HTMLCanvasElement} canvas - Elemen canvas.
         * @param {MouseEvent} event - Event mouse.
         * @returns {Object} Objek dengan properti x dan y.
         */
        function getMousePos(canvas, event) {
            const rect = canvas.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            return {
                x: (event.clientX - rect.left) / rect.width * canvas.width / dpr,
                y: (event.clientY - rect.top) / rect.height * canvas.height / dpr
            };
        }

        // Event listener untuk tombol hapus
        deleteSignatureButton.addEventListener('click', function() {
            if (hasSignature && signatureImage.value) {
                clearCanvas();
            }
        });

        // Ubah icon tombol saat di hover
        deleteSignatureButton.addEventListener('mouseover', function() {
            if (hasSignature && signatureImage.value) {
                deleteSignatureButton.src = 'assets/img/icon_delete_hover.svg';
            }
        });

        deleteSignatureButton.addEventListener('mouseout', function() {
            updateDeleteButtonState();
        });

        // Event listener untuk menggambar
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        // Pastikan placeholder tampil jika canvas kosong saat pertama kali dimuat
        showPlaceholder();

        /**
         * Fungsi untuk menonaktifkan kanvas (mencegah menggambar).
         */
        function disableCanvas() {
            drawingEnabled = false;
            canvas.style.pointerEvents = 'none'; // Menghapus kemampuan interaksi pengguna
        }

        /**
         * Fungsi untuk mengaktifkan kanvas (memungkinkan menggambar).
         */
        function enableCanvas() {
            drawingEnabled = true;
            canvas.style.pointerEvents = 'auto'; // Mengembalikan kemampuan interaksi pengguna
        }
    });

    // Mengelola toggle petunjuk
    document.addEventListener('DOMContentLoaded', function() {
        // Mendapatkan elemen petunjuk
        const petunjuk = document.querySelector('.petunjuk');
        const iconParent = petunjuk.querySelector('.iconoutlineinformation-circl-parent');
        const iconRotasi = petunjuk.querySelector('.iconoutlineinformation-circl1');

        // Menambahkan event listener untuk klik pada tombol petunjuk
        iconParent.addEventListener('click', function() {
            // Toggle kelas 'active' pada elemen petunjuk
            petunjuk.classList.toggle('active');
        });
    });

    // Mengelola tombol download
    document.addEventListener('DOMContentLoaded', function() {
        // Mendapatkan semua elemen dengan kelas 'iconsoliddownload'
        const iconDownloadElements = document.querySelectorAll('.iconsoliddownload');

        // Mengubah src setiap ikon download ke 'icon_download.svg' saat halaman dimuat
        iconDownloadElements.forEach(function(icon) {
            icon.src = 'assets/img/icon_download.svg';

            // Menyembunyikan semua elemen dalam 'download-button' kecuali ikon download
            const downloadButton = icon.closest('.download-button, .download-button1, .download-button2');
            if (downloadButton) {
                // Menyembunyikan semua anak elemen dari downloadButton
                const children = downloadButton.children;
                for (let i = 0; i < children.length; i++) {
                    // Menyembunyikan semua elemen kecuali yang memiliki kelas 'iconsoliddownload'
                    if (!children[i].classList.contains('iconsoliddownload')) {
                        children[i].style.opacity = '0';
                        children[i].style.pointerEvents = 'none';
                        children[i].style.transition = 'opacity 0.1s ease-in-out'; // Menambahkan transisi
                    }
                }
            }
        });

        // Menambahkan event listeners untuk hover pada setiap ikon download
        iconDownloadElements.forEach(function(icon) {
            // Mendapatkan parent download-button
            const downloadButton = icon.closest('.download-button, .download-button1, .download-button2');
            if (!downloadButton) return;

            /**
             * Event saat mouse masuk ke ikon download.
             */
            icon.addEventListener('mouseenter', function() {
                // Mengubah src ikon download ke 'icon_download_variant1.svg'
                icon.src = 'assets/img/icon_download_variant1.svg';

                // Menampilkan semua elemen dalam download-button dengan animasi fade
                const children = downloadButton.children;
                for (let i = 0; i < children.length; i++) {
                    if (!children[i].classList.contains('iconsoliddownload')) {
                        children[i].style.opacity = '1';
                        children[i].style.pointerEvents = 'auto';
                    }
                }
            });

            /**
             * Event saat mouse keluar dari ikon download.
             */
            icon.addEventListener('mouseleave', function() {
                // Mengubah kembali src ikon download ke 'icon_download.svg'
                icon.src = 'assets/img/icon_download.svg';

                // Menyembunyikan semua elemen dalam download-button kecuali ikon download dengan animasi fade
                const children = downloadButton.children;
                for (let i = 0; i < children.length; i++) {
                    if (!children[i].classList.contains('iconsoliddownload')) {
                        children[i].style.opacity = '0';
                        children[i].style.pointerEvents = 'none';
                    }
                }
            });

            /**
             * Menambahkan event click untuk tombol download.
             * @param {Event} e - Event klik.
             */
            downloadButton.addEventListener('click', function(e) {
                // Mencegah navigasi default jika perlu
                e.preventDefault();

                // Mendapatkan file path dan nama file dari data attributes
                let filePath = downloadButton.getAttribute('data-file');
                let fileName = downloadButton.getAttribute('data-filename');

                if (filePath && filePath !== '') {
                    // Mengecek apakah file ada di server
                    fetch(filePath)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('File tidak tersedia di situs');
                            }
                            return response.blob();
                        })
                        .then(blob => {
                            // Membuat URL blob untuk download
                            const url = window.URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.href = url;
                            link.download = fileName;
                            document.body.appendChild(link);
                            link.click();
                            link.remove();
                            window.URL.revokeObjectURL(url);
                        })
                        .catch(error => {
                            // Menampilkan SweetAlert jika terjadi error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'File tidak tersedia di situs.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#2563EB'
                            });
                        });
                } else {
                    // Menampilkan SweetAlert jika file tidak tersedia
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'File tidak tersedia untuk diunduh.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563EB'
                    });
                }
            });
        });
    });

    // Mengelola animasi scroll pada elemen teks panjang
    window.onload = function() {
        // Daftar pasangan elemen target dan induknya (tidak ada pasangan yang didefinisikan)
        const targetPairs = [];

        // Daftar kelas atau atribut data yang akan dianimasikan secara langsung
        const directClassesToAnimate = [
            "nama-pengguna",
            "email-pengguna",
            "xi-rekayasa-perangkat",
            "dasar-pemrograman1",
            "kompetensi-dasar-menerapkan-container",
            "courtney-henry"
        ];

        // Gabungan semua elemen yang perlu dianimasikan
        const elementsToAnimate = [];

        /**
         * Fungsi untuk memeriksa apakah elemen benar-benar overflow.
         * @param {HTMLElement} element - Elemen yang ingin diperiksa.
         * @returns {Object} Objek yang berisi informasi overflow.
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
         * Fungsi untuk menangani animasi scroll pada elemen tertentu.
         * @param {HTMLElement} element - Elemen yang akan dianimasikan.
         */
        function setupScrollAnimation(element) {
            const overflowInfo = isElementOverflowing(element);

            if (!overflowInfo.overflowing) {
                return;
            }

            // Simpan teks asli dalam data attribute untuk pemulihan nanti
            const originalText = element.textContent.trim();
            element.setAttribute('data-original-text', originalText);

            // Terapkan gaya default dengan ellipsis
            element.style.overflow = "hidden";
            element.style.textOverflow = "ellipsis";
            element.style.whiteSpace = "nowrap";

            /**
             * Fungsi untuk memulai animasi scroll.
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
                 * Fungsi animasi menggunakan requestAnimationFrame.
                 * @param {DOMHighResTimeStamp} timestamp - Waktu saat frame.
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
             * Fungsi untuk menghentikan animasi scroll dan mengembalikan posisi awal.
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

        // Mengolah elemen-elemen yang dianimasikan secara langsung berdasarkan kelas
        directClassesToAnimate.forEach(className => {
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

                // Periksa kembali overflow dan terapkan animasi jika perlu
                setupScrollAnimation(element);

                const overflowInfo = isElementOverflowing(element);
                if (overflowInfo.overflowing) {
                    if (item.type === 'direct') {
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

    // Variabel untuk menandai apakah form telah diubah
    let isFormDirty = false;

    // Menangani konfirmasi saat berpindah halaman tanpa menyimpan
    document.addEventListener('DOMContentLoaded', function() {
        const saveFormButton = document.getElementById('saveForm');
        const observationForm = document.getElementById('observationForm');

        // Menangani konfirmasi saat berpindah halaman melalui link
        document.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (isFormDirty) {
                    e.preventDefault(); // Mencegah navigasi langsung

                    const href = this.getAttribute('href');

                    // Menampilkan SweetAlert dengan opsi untuk menyimpan atau tetap
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
                            // Jika pengguna memilih untuk menyimpan, trigger klik tombol simpan
                            saveFormButton.setAttribute('data-redirect', href); // Set atribut untuk redirect setelah simpan
                            saveFormButton.click();
                        } else if (result.isDenied) {
                            // Jika pengguna memilih untuk meninggalkan halaman, navigasi ke href
                            window.location.href = href;
                        }
                        // Jika pengguna memilih 'Batal', tidak melakukan apa-apa
                    });
                }
            });
        });

        /**
         * Fungsi untuk mengecek apakah ada checkbox yang dipilih.
         * @returns {boolean} True jika ada checkbox yang dicentang, false jika tidak.
         */
        function isAnyCheckboxChecked() {
            const checkboxes = document.querySelectorAll('.checkbox-icon, .checkbox.active');
            return checkboxes.length > 0;
        }

        // Menambahkan event listener untuk tombol simpan untuk menangani redirect setelah simpan
        saveFormButton.addEventListener('click', function(e) {
            const redirectUrl = this.getAttribute('data-redirect');
            if (redirectUrl) {
                // Setelah form disubmit, redirect ke URL yang diinginkan
                // Menggunakan event listener untuk menangani redirect setelah form disubmit
                observationForm.addEventListener('submit', function onSubmit() {
                    // Setelah submit, navigasi ke href
                    window.location.href = redirectUrl;
                    // Menghapus event listener setelah navigasi
                    observationForm.removeEventListener('submit', onSubmit);
                });
            }
        });
    });

    // Menangani interaksi checkbox
    document.addEventListener('DOMContentLoaded', function() {
        // Mendapatkan semua elemen checkbox
        const checkboxIcons = document.querySelectorAll('.checkbox-icon');
        const checkboxes = document.querySelectorAll('.checkbox');

        // Mendapatkan data dari form yang telah diisi sebelumnya
        let observationData = {};

        // Mengisi observationData dengan data dari PHP
        <?php if (isset($observation_details_json)): ?>
            observationData = <?= $observation_details_json; ?>;
        <?php endif; ?>

        // Menangani klik pada checkbox individu
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('click', function() {
                // Memeriksa apakah checkbox dikunci oleh "Pilih Semua"
                if (this.getAttribute('data-disabled') === 'true') {
                    return; // Mengabaikan klik jika checkbox dikunci
                }

                this.classList.toggle('active');
                updateCheckboxIcon(this);
                isFormDirty = true; // Menandai bahwa form telah diubah
                updateSelectAllButton(this.getAttribute('data-indicator'));
            });
        });

        // Menangani klik pada tombol "Pilih Semua"
        const selectAllButtons = document.querySelectorAll('.checkbox.select-all');
        selectAllButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const indicator = this.getAttribute('data-indicator');
                const checkboxes = document.querySelectorAll('.checkbox[data-indicator="' + indicator + '"]');

                // Tentukan apakah akan memilih semua atau membatalkan pilihan
                const allChecked = Array.from(checkboxes).every(cb => cb.classList.contains('active'));

                checkboxes.forEach(function(cb) {
                    if (allChecked) {
                        cb.classList.remove('active');
                        cb.setAttribute('data-value', '0');
                        cb.removeAttribute('data-disabled'); // Mengizinkan checkbox diubah
                    } else {
                        cb.classList.add('active');
                        cb.setAttribute('data-value', '1');
                        cb.setAttribute('data-disabled', 'true'); // Mengunci checkbox
                    }
                    updateCheckboxIcon(cb);
                });

                isFormDirty = true; // Menandai bahwa form telah diubah
            });
        });

        /**
         * Fungsi untuk memperbarui ikon checkbox.
         * @param {HTMLElement} checkbox - Elemen checkbox yang diperbarui.
         */
        function updateCheckboxIcon(checkbox) {
            if (checkbox.classList.contains('select-all')) {
                // Jika ini adalah tombol "Pilih Semua", jangan tambahkan margin-right
                if (checkbox.classList.contains('active')) {
                    // Ganti dengan ikon checked
                    const img = document.createElement('img');
                    img.classList.add('checkbox-icon');
                    img.setAttribute('alt', 'Checked');
                    img.setAttribute('src', 'assets/img/icon_check.svg');
                    img.setAttribute('data-indicator', checkbox.getAttribute('data-indicator'));
                    img.setAttribute('data-student', 'all'); // Indikator 'all' untuk "Pilih Semua"
                    img.setAttribute('data-value', '1');
                    img.style.display = 'inline-block';
                    checkbox.parentNode.replaceChild(img, checkbox);
                    addCheckboxIconListener(img);
                } else {
                    // Ganti kembali dengan checkbox kosong
                    const div = document.createElement('div');
                    div.classList.add('checkbox', 'select-all');
                    div.setAttribute('data-indicator', checkbox.getAttribute('data-indicator'));
                    div.setAttribute('data-student', 'all'); // Indikator 'all' untuk "Pilih Semua"
                    div.setAttribute('data-value', '0');
                    const innerDiv = document.createElement('div');
                    innerDiv.classList.add('large-ico-check');
                    div.appendChild(innerDiv);
                    div.style.display = 'inline-block';
                    checkbox.parentNode.replaceChild(div, checkbox);
                    addCheckboxListener(div);
                }
            } else {
                // Untuk checkbox individu, tambahkan margin-right
                if (checkbox.classList.contains('active')) {
                    // Ganti dengan ikon checked
                    const img = document.createElement('img');
                    img.classList.add('checkbox-icon');
                    img.setAttribute('alt', 'Checked');
                    img.setAttribute('src', 'assets/img/icon_check.svg');
                    img.setAttribute('data-indicator', checkbox.getAttribute('data-indicator'));
                    img.setAttribute('data-student', checkbox.getAttribute('data-student'));
                    img.setAttribute('data-value', '1');
                    // Menambahkan CSS margin-right melalui JavaScript untuk menjaga jarak antar checkbox
                    img.style.marginRight = '2rem';
                    img.style.display = 'inline-block';
                    checkbox.parentNode.replaceChild(img, checkbox);
                    addCheckboxIconListener(img);
                } else {
                    // Ganti kembali dengan checkbox kosong
                    const div = document.createElement('div');
                    div.classList.add('checkbox');
                    div.setAttribute('data-indicator', checkbox.getAttribute('data-indicator'));
                    div.setAttribute('data-student', checkbox.getAttribute('data-student'));
                    div.setAttribute('data-value', '0');
                    const innerDiv = document.createElement('div');
                    innerDiv.classList.add('large-ico-check');
                    div.appendChild(innerDiv);
                    // Menambahkan CSS margin-right melalui JavaScript untuk menjaga jarak antar checkbox
                    div.style.marginRight = '2rem';
                    div.style.display = 'inline-block';
                    checkbox.parentNode.replaceChild(div, checkbox);
                    addCheckboxListener(div);
                }
            }
        }

        /**
         * Fungsi untuk menambahkan event listener pada checkbox-icon.
         * @param {HTMLElement} icon - Elemen ikon checkbox.
         */
        function addCheckboxIconListener(icon) {
            icon.addEventListener('click', function() {
                // Memeriksa apakah checkbox dikunci oleh "Pilih Semua"
                const indicator = icon.getAttribute('data-indicator');
                const selectAllCheckbox = document.querySelector('.checkbox.select-all[data-indicator="' + indicator + '"] img.checkbox-icon[data-value="1"]');

                if (selectAllCheckbox) {
                    // Jika "Pilih Semua" aktif, tidak mengizinkan mengubah checkbox individu
                    return;
                }

                // Ganti dengan checkbox kosong
                const div = document.createElement('div');
                if (icon.getAttribute('data-student') === 'all') {
                    div.classList.add('checkbox', 'select-all');
                } else {
                    div.classList.add('checkbox');
                }
                div.setAttribute('data-indicator', icon.getAttribute('data-indicator'));
                div.setAttribute('data-student', icon.getAttribute('data-student'));
                div.setAttribute('data-value', '0');
                const innerDiv = document.createElement('div');
                innerDiv.classList.add('large-ico-check');
                div.appendChild(innerDiv);
                if (icon.getAttribute('data-student') !== 'all') {
                    div.style.marginRight = '2rem';
                }
                div.style.display = 'inline-block';
                icon.parentNode.replaceChild(div, icon);
                addCheckboxListener(div);
                isFormDirty = true; // Menandai bahwa form telah diubah
            });
        }

        /**
         * Fungsi untuk menambahkan event listener pada checkbox.
         * @param {HTMLElement} checkbox - Elemen checkbox.
         */
        function addCheckboxListener(checkbox) {
            checkbox.addEventListener('click', function() {
                // Memeriksa apakah checkbox dikunci oleh "Pilih Semua"
                if (this.getAttribute('data-disabled') === 'true') {
                    return; // Mengabaikan klik jika checkbox dikunci
                }

                this.classList.toggle('active');
                updateCheckboxIcon(this);
                isFormDirty = true; // Menandai bahwa form telah diubah
                updateSelectAllButton(this.getAttribute('data-indicator'));
            });
        }

        // Menambahkan event listener pada checkbox-icon yang sudah ada
        checkboxIcons.forEach(function(icon) {
            addCheckboxIconListener(icon);
        });

        /**
         * Fungsi untuk memperbarui status tombol "Pilih Semua".
         * @param {string} indicator - Indikator yang terkait.
         */
        function updateSelectAllButton(indicator) {
            const selectAllButton = document.querySelector('.checkbox.select-all[data-indicator="' + indicator + '"] img.checkbox-icon[data-value="1"]');
            const checkboxes = document.querySelectorAll('.checkbox[data-indicator="' + indicator + '"]');

            if (selectAllButton) {
                // Jika "Pilih Semua" aktif, set 'data-disabled'='true' pada checkbox individu
                checkboxes.forEach(function(cb) {
                    cb.setAttribute('data-disabled', 'true');
                });
            } else {
                // Jika tidak, hapus 'data-disabled' dari checkbox individu
                checkboxes.forEach(function(cb) {
                    cb.removeAttribute('data-disabled');
                });
            }
        }
    });

    // Menangani pengiriman data checkbox ke server saat submit
    document.addEventListener('DOMContentLoaded', function() {
        const observationForm = document.getElementById('observationForm');

        /**
         * Event listener untuk menangani pengiriman data checkbox ke server.
         */
        observationForm.addEventListener('submit', function(e) {
            // Mendapatkan semua checkbox, baik yang dicentang maupun tidak
            const allCheckboxes = document.querySelectorAll('.checkbox-icon, .checkbox');

            const observationDetails = {};

            allCheckboxes.forEach(function(checkbox) {
                const indicator = checkbox.getAttribute('data-indicator');
                const student = checkbox.getAttribute('data-student');
                const value = checkbox.getAttribute('data-value') === '1' ? 1 : 0;

                if (!observationDetails[indicator]) {
                    observationDetails[indicator] = {};
                }
                observationDetails[indicator][student] = value;
            });

            // Membuat input hidden untuk mengirim data ke server
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'observation_details';
            input.value = JSON.stringify(observationDetails);
            observationForm.appendChild(input);
        });
    });

    // Mengelola pergeseran kolom nomor siswa dan checkbox
    document.addEventListener('DOMContentLoaded', function() {
        // Mendapatkan elemen-elemen yang diperlukan
        const studentNumberList = document.querySelector('.student-number-list');
        const checkboxLists = document.querySelectorAll('.checkbox-list');
        const shiftLeftButton = document.getElementById('shiftLeft');
        const shiftRightButton = document.getElementById('shiftRight');

        // Menghitung total student numbers
        const totalStudents = <?php echo count(array_filter($student_numbers)); ?>; // Menghitung hanya yang tidak kosong
        const columnsToShow = 5; // Pastikan menampilkan 5 kolom
        let currentIndex = 0;

        // Mengatur lebar container berdasarkan (1.25rem + 2rem) * 5
        const rem = parseFloat(getComputedStyle(document.documentElement).fontSize); // Mengambil nilai rem dalam px
        const columnWidth = (1.25 * rem) + (2 * rem); // Lebar satu kolom
        const containerWidth = columnWidth * columnsToShow; // Lebar container untuk 5 kolom

        // Mengatur lebar 'number-scroll-container' dan 'checkbox-scroll-container'
        const numberScrollContainer = document.querySelector('.number-scroll-container');
        numberScrollContainer.style.width = `${containerWidth}px`;
        numberScrollContainer.style.overflow = 'hidden';
        numberScrollContainer.style.position = 'relative'; // Menambahkan posisi relatif untuk tombol shift

        checkboxLists.forEach(function(checkboxList) {
            const checkboxScrollContainer = checkboxList.parentElement;
            checkboxScrollContainer.style.width = `${containerWidth}px`;
            checkboxScrollContainer.style.overflow = 'hidden';
            checkboxScrollContainer.style.position = 'relative'; // Menambahkan posisi relatif untuk tombol shift
        });

        // Mengatur style untuk 'number-scroll-wrapper' agar berada dalam satu baris
        const numberScrollWrapper = document.querySelector('.number-scroll-wrapper');
        numberScrollWrapper.style.display = 'flex';
        numberScrollWrapper.style.alignItems = 'center';
        numberScrollWrapper.style.justifyContent = 'center';
        numberScrollWrapper.style.position = 'relative'; // Menambahkan posisi relatif untuk tombol shift

        // Mengatur style untuk 'student-number-list' dan 'checkbox-list'
        studentNumberList.style.display = 'flex';
        studentNumberList.style.transition = 'transform 0.3s ease';
        studentNumberList.style.position = 'relative'; // Menambahkan posisi relatif

        checkboxLists.forEach(function(checkboxList) {
            checkboxList.style.display = 'flex';
            checkboxList.style.transition = 'transform 0.3s ease';
            checkboxList.style.position = 'relative'; // Menambahkan posisi relatif
        });

        // Mengatur style untuk 'student-number' dan 'checkbox' elemen
        const studentNumbers = document.querySelectorAll('.student-number-list .student-number');
        studentNumbers.forEach(function(element) {
            element.style.flexShrink = '0';
            element.style.marginRight = '2rem'; // Gap antar nomor siswa
            element.style.position = 'relative'; // Menambahkan posisi relatif
            element.style.width = '1.25rem'; // Menetapkan lebar 1.25rem
            element.style.overflow = 'visible'; // Menetapkan overflow ke visible
        });

        const checkboxes = document.querySelectorAll('.checkbox-list .checkbox, .checkbox-list .checkbox-icon');
        checkboxes.forEach(function(element) {
            element.style.flexShrink = '0';
            element.style.marginRight = '2rem'; // Gap antar checkbox
            element.style.position = 'relative'; // Menambahkan posisi relatif
        });

        // Menambahkan CSS melalui JavaScript untuk shift buttons
        shiftLeftButton.style.position = 'absolute';
        shiftLeftButton.style.left = '-3rem'; // Gap 3rem dari daftar nomor siswa
        shiftLeftButton.style.top = '50%';
        shiftLeftButton.style.transform = 'translateY(-50%)';
        shiftLeftButton.style.marginRight = '1rem'; // Gap antara tombol shift dan daftar
        shiftLeftButton.style.backgroundColor = '#3B82F6';
        shiftLeftButton.style.color = '#fff';
        shiftLeftButton.style.border = 'none';
        shiftLeftButton.style.borderRadius = '8px';
        shiftLeftButton.style.padding = '0.5rem';
        shiftLeftButton.style.cursor = 'pointer';

        shiftRightButton.style.position = 'absolute';
        shiftRightButton.style.right = '-1rem'; // Gap 1rem dari daftar nomor siswa
        shiftRightButton.style.top = '50%';
        shiftRightButton.style.transform = 'translateY(-50%)';
        shiftRightButton.style.marginLeft = '1rem'; // Gap antara tombol shift dan daftar
        shiftRightButton.style.backgroundColor = '#3B82F6';
        shiftRightButton.style.color = '#fff';
        shiftRightButton.style.border = 'none';
        shiftRightButton.style.borderRadius = '8px';
        shiftRightButton.style.padding = '0.5rem';
        shiftRightButton.style.cursor = 'pointer';

        /**
         * Fungsi untuk mengupdate tampilan tombol shift.
         */
        function updateShiftButtons() {
            if (currentIndex <= 0) {
                shiftLeftButton.style.display = 'none';
            } else {
                shiftLeftButton.style.display = 'block';
            }

            if (currentIndex >= totalStudents - columnsToShow) {
                shiftRightButton.style.display = 'none';
            } else {
                shiftRightButton.style.display = 'block';
            }
        }

        /**
         * Fungsi untuk menggeser tampilan.
         */
        function shiftView() {
            const shiftAmount = -currentIndex * columnWidth;
            studentNumberList.style.transform = 'translateX(' + shiftAmount + 'px)';
            checkboxLists.forEach(function(checkboxList) {
                checkboxList.style.transform = 'translateX(' + shiftAmount + 'px)';
            });
            updateShiftButtons();
        }

        // Event listener untuk shiftLeftButton
        shiftLeftButton.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                shiftView();
            }
        });

        // Event listener untuk shiftRightButton
        shiftRightButton.addEventListener('click', function() {
            if (currentIndex < totalStudents - columnsToShow) {
                currentIndex++;
                shiftView();
            }
        });

        // Inisialisasi tampilan
        updateShiftButtons();
        shiftView();

        // Mengatur display tombol shift
        if (totalStudents <= columnsToShow) {
            shiftLeftButton.style.display = 'none';
            shiftRightButton.style.display = 'none';
        }
    });
</script>

</html>