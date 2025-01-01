<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <base href="<?= base_url(); ?>">
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
                <div class="timer" data-end-time="<?= $class_end_datetime->format('Y-m-d H:i:s'); ?>"></div>
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
                            <textarea name="notes" id="note"><?= isset($observation_sheet->notes) ? htmlspecialchars($observation_sheet->notes, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
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

    // Memanggil fungsi updateDateTime secara terus-menerus tanpa jeda
    setInterval(updateDateTime, 0);

    // Memastikan waktu saat ini ditampilkan saat memuat halaman
    updateDateTime();

    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen timer
        const timerElement = document.querySelector('.timer');
        // Ambil waktu akhir kelas dari data atribut
        const endTimeString = timerElement.getAttribute('data-end-time');

        // Konversi string waktu akhir kelas ke objek Date
        const endTime = new Date(endTimeString);

        // Interval untuk menghitung mundur
        let countdownInterval = setInterval(updateTimer, 1000);

        // Fungsi untuk memperbarui tampilan timer setiap detik
        function updateTimer() {
            const now = new Date();
            const diff = endTime - now; // selisih dalam milidetik

            if (diff <= 0) {
                // Jika waktu habis, hentikan interval
                clearInterval(countdownInterval);
                timerElement.innerText = "00:00";

                // Tampilkan SweetAlert2 dengan tombol "Simpan Formulir" saja
                Swal.fire({
                    title: 'Waktu telah habis',
                    text: 'Silakan simpan formulir Anda.',
                    icon: 'info',
                    showCancelButton: false,
                    confirmButtonText: 'Simpan Formulir',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonColor: '#2563EB'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tekan tombol #saveForm untuk langsung men-submit form
                        document.getElementById('saveForm').click();
                        // Tidak ada logika tampilan sukses tambahan di sini.
                        // Setelah ini, proses dan alur selanjutnya ditangani oleh server atau halaman target.
                    }
                });

                return; // Keluar dari fungsi updateTimer karena waktu sudah habis
            }

            // Hitung sisa menit dan detik
            const seconds = Math.floor(diff / 1000);
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;

            // Format tampilan mm:ss
            const formattedMinutes = String(minutes).padStart(2, '0');
            const formattedSeconds = String(remainingSeconds).padStart(2, '0');
            timerElement.innerText = `${formattedMinutes}:${formattedSeconds}`;

            // Ubah warna teks sesuai sisa waktu
            // Jika kurang 15 menit (900 detik) menjadi kuning
            if (seconds <= 900 && seconds > 300) {
                timerElement.style.color = '#F59E0B';
                timerElement.classList.remove('heartbeat');
            }

            // Jika kurang 5 menit (300 detik) menjadi merah
            if (seconds <= 300 && seconds > 60) {
                timerElement.style.color = '#EF4444';
                timerElement.classList.remove('heartbeat');
            }

            // Jika kurang 1 menit (60 detik) menjadi merah dan hitam (berdetak)
            if (seconds <= 60) {
                timerElement.style.color = '#EF4444';
                timerElement.classList.add('heartbeat');
            }
        }

        // Jalankan updateTimer pertama kali agar timer langsung diperbarui
        updateTimer();
    });

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

    // Mengelola tanda tangan pada canvas
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signatureCanvas');
        const signatureImage = document.getElementById('signatureImage');
        const deleteSignatureButton = document.getElementById('deleteSignature');
        const placeholderCanvas = document.getElementById('placeholderCanvas');
        const context = canvas.getContext('2d');
        context.imageSmoothingEnabled = true;
        context.imageSmoothingQuality = 'high';
        let isDrawing = false;
        let hasSignature = false;
        let drawingEnabled = true; // Flag untuk mengontrol apakah pengguna dapat menggambar
        let signatureChanged = false; // Variabel untuk menandai apakah tanda tangan telah diubah

        const baseLineWidth = 2; // Ketebalan garis dasar
        const strokes = []; // Menyimpan semua strokes
        let currentStroke = []; // Menyimpan stroke saat ini

        /**
         * Fungsi untuk mengatur ukuran canvas sesuai dengan CSS dan DPR (Device Pixel Ratio)
         */
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            context.scale(dpr, dpr);
            // Atur properti anti-aliasing
            context.lineCap = 'round';
            context.lineJoin = 'round';
            context.strokeStyle = '#000';
            context.lineWidth = baseLineWidth; // Tetapkan ketebalan garis tetap

            // Jika sudah ada tanda tangan, gambar kembali dari src_signature_file
            if (signatureImage.value) {
                const img = new Image();
                img.src = signatureImage.value;

                img.onload = function() {
                    // Gambar ulang tanda tangan yang sudah diproses ke tengah canvas
                    const canvasWidth = canvas.width / dpr; // 500
                    const canvasHeight = canvas.height / dpr; // 500

                    // Hitung skala agar gambar sesuai dengan canvas virtual dengan padding 5px
                    const padding = 5; // Padding tetap 5px
                    const availableWidth = canvasWidth - 2 * padding;
                    const availableHeight = canvasHeight - 2 * padding;
                    const scaleWidth = availableWidth / img.width;
                    const scaleHeight = availableHeight / img.height;
                    const scale = Math.min(scaleWidth, scaleHeight);

                    const scaledWidth = img.width * scale;
                    const scaledHeight = img.height * scale;

                    const x = padding + (availableWidth - scaledWidth) / 2;
                    const y = padding + (availableHeight - scaledHeight) / 2;

                    // Atur lineWidth agar tetap konsisten 2px
                    context.lineWidth = baseLineWidth; // Tetap 2px

                    // Clear canvas sebelum menggambar ulang
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    context.drawImage(img, x, y, scaledWidth, scaledHeight);
                    hasSignature = true;
                    updateDeleteButtonState();
                    hidePlaceholder();
                    disableCanvas(); // Nonaktifkan kanvas jika ada tanda tangan
                };
            } else {
                hasSignature = false;
                updateDeleteButtonState();
                showPlaceholder();
            }
        }

        // Panggil resizeCanvas saat halaman dimuat
        resizeCanvas();

        // Menyesuaikan ukuran canvas saat seluruh halaman telah dimuat
        window.addEventListener('load', function() {
            resizeCanvas(); // Panggil resizeCanvas pertama kali
            setTimeout(resizeCanvas, 100); // Panggil ulang setelah 100ms untuk memastikan ukuran yang akurat
        });

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
         * Fungsi untuk menggambar stroke dengan Bezier Curves untuk hasil yang lebih mulus
         * @param {CanvasRenderingContext2D} ctx - Konteks canvas
         * @param {Array} stroke - Array titik-titik stroke
         */
        function drawSmoothStroke(ctx, stroke) {
            if (stroke.length < 2) {
                return;
            }

            ctx.beginPath();
            ctx.moveTo(stroke[0].x, stroke[0].y);

            for (let i = 1; i < stroke.length - 1; i++) {
                const midPoint = {
                    x: (stroke[i].x + stroke[i + 1].x) / 2,
                    y: (stroke[i].y + stroke[i + 1].y) / 2
                };
                ctx.quadraticCurveTo(stroke[i].x, stroke[i].y, midPoint.x, midPoint.y);
            }

            // Garis terakhir
            ctx.lineTo(stroke[stroke.length - 1].x, stroke[stroke.length - 1].y);
            ctx.stroke();
        }

        /**
         * Fungsi untuk memulai menggambar
         * @param {Event} event - Event mouse down
         */
        function startDrawing(event) {
            if (!drawingEnabled) return; // Jika kanvas dinonaktifkan, tidak boleh menggambar
            isDrawing = true;
            hasSignature = true; // Menandai bahwa mulai menggambar, menghilangkan placeholder
            signatureChanged = true; // Menandai tanda tangan telah diubah
            document.getElementById('signatureChanged').value = "1"; // Mengatur nilai input tersembunyi
            const {
                x,
                y
            } = getMousePos(canvas, event);
            currentStroke = [{
                x,
                y
            }];
            context.beginPath();
            context.moveTo(x, y);
            context.lineWidth = 1;
            hidePlaceholder(); // Pastikan placeholder disembunyikan saat mulai menggambar
        }

        /**
         * Fungsi untuk menggambar di canvas
         * @param {Event} event - Event mouse move
         */
        function draw(event) {
            if (!isDrawing || !drawingEnabled) return;
            const {
                x,
                y
            } = getMousePos(canvas, event);
            currentStroke.push({
                x,
                y
            });
            context.lineTo(x, y);
            context.stroke();
            // hasSignature sudah di-set ke true di startDrawing
        }

        /**
         * Fungsi untuk berhenti menggambar
         */
        function stopDrawing() {
            if (isDrawing) {
                isDrawing = false;
                if (currentStroke.length > 0) {
                    strokes.push(currentStroke);
                    currentStroke = [];
                    updateSignatureImage();
                }
            }
        }

        /**
         * Fungsi untuk memperbarui gambar tanda tangan ke dalam input (PNG)
         */
        async function updateSignatureImage() {
            if (strokes.length > 0) {
                const processedDataUrl = await processSignature();
                if (processedDataUrl) {
                    signatureImage.value = processedDataUrl;
                    signatureChanged = true; // Menandai tanda tangan telah diubah
                    document.getElementById('signatureChanged').value = "1"; // Mengatur nilai input tersembunyi
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
         * Fungsi untuk memproses tanda tangan: cropping dan scaling dengan padding 5px
         * @returns {Promise<string>} - Data URL dari tanda tangan yang diproses
         */
        function processSignature() {
            return new Promise((resolve) => {
                if (strokes.length === 0) {
                    resolve('');
                    return;
                }

                // Membuat canvas virtual dengan ukuran tetap 1000x1000 untuk resolusi lebih tinggi
                const virtualCanvas = document.createElement('canvas');
                const virtualContext = virtualCanvas.getContext('2d');
                const virtualSize = 1000; // Ukuran ditingkatkan untuk resolusi lebih tinggi
                const dpr = window.devicePixelRatio || 1;
                virtualCanvas.width = virtualSize * dpr;
                virtualCanvas.height = virtualSize * dpr;
                virtualContext.scale(dpr, dpr);

                // Hitung bounding box dari strokes
                let minX = Infinity,
                    minY = Infinity,
                    maxX = -Infinity,
                    maxY = -Infinity;
                strokes.forEach(stroke => {
                    stroke.forEach(point => {
                        if (point.x < minX) minX = point.x;
                        if (point.y < minY) minY = point.y;
                        if (point.x > maxX) maxX = point.x;
                        if (point.y > maxY) maxY = point.y;
                    });
                });

                const croppedWidth = maxX - minX;
                const croppedHeight = maxY - minY;

                // Jika tidak ada tanda tangan, return kosong
                if (croppedWidth === 0 || croppedHeight === 0) {
                    resolve('');
                    return;
                }

                // Tambahkan padding fixed 5px
                const padding = 5; // Padding fixed 5px
                const availableWidth = virtualSize - 2 * padding;
                const availableHeight = virtualSize - 2 * padding;

                // Hitung skala agar tanda tangan sesuai dengan virtual canvas dengan padding 5px
                const scaleWidth = availableWidth / croppedWidth;
                const scaleHeight = availableHeight / croppedHeight;
                const scale = Math.min(scaleWidth, scaleHeight);

                const scaledWidth = croppedWidth * scale;
                const scaledHeight = croppedHeight * scale;

                // Hitung posisi untuk menempatkan tanda tangan di tengah virtual canvas dengan padding 5px
                const offsetX = padding + (availableWidth - scaledWidth) / 2 - (minX * scale);
                const offsetY = padding + (availableHeight - scaledHeight) / 2 - (minY * scale);

                // Atur properti menggambar
                virtualContext.lineCap = 'round';
                virtualContext.lineJoin = 'round';
                virtualContext.strokeStyle = '#000';
                virtualContext.lineWidth = baseLineWidth * scale; // Sesuaikan ketebalan garis

                // Clear canvas virtual sebelum menggambar
                virtualContext.clearRect(0, 0, virtualSize, virtualSize);

                // Menggambar setiap stroke dengan smoothing
                strokes.forEach(stroke => {
                    drawSmoothStroke(virtualContext, stroke.map(point => ({
                        x: point.x * scale + offsetX,
                        y: point.y * scale + offsetY
                    })));
                });

                // Mendapatkan data URL dari virtualCanvas dengan kualitas tinggi
                resolve(virtualCanvas.toDataURL('image/png', 1.0)); // 1.0 untuk kualitas maksimal
            });
        }

        /**
         * Cek apakah canvas kosong
         * @returns {boolean} - True jika canvas kosong, false jika tidak
         */
        function isCanvasBlank() {
            return strokes.length === 0 && !hasSignature;
        }

        /**
         * Fungsi untuk menyembunyikan placeholder
         */
        function hidePlaceholder() {
            if (!isCanvasBlank()) {
                placeholderCanvas.style.display = 'none';
            }
        }

        /**
         * Fungsi untuk menampilkan placeholder
         */
        function showPlaceholder() {
            if (isCanvasBlank()) {
                placeholderCanvas.style.display = 'block';
            }
        }

        /**
         * Perbarui status tombol delete
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
         * Fungsi untuk menghapus tanda tangan
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
                    strokes.length = 0; // Mengosongkan semua strokes
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
         * Fungsi untuk mendapatkan posisi mouse relatif terhadap canvas
         * @param {HTMLCanvasElement} canvas - Elemen canvas
         * @param {Event} event - Event mouse
         * @returns {Object} - Objek dengan koordinat x dan y
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
         * Fungsi untuk menonaktifkan kanvas (mencegah menggambar)
         */
        function disableCanvas() {
            drawingEnabled = false;
            canvas.style.pointerEvents = 'none'; // Menghapus kemampuan interaksi pengguna
        }

        /**
         * Fungsi untuk mengaktifkan kanvas (memungkinkan menggambar)
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
             * Menambahkan event click untuk tombol download dengan SweetAlert2 Loading
             */
            downloadButton.addEventListener('click', function(e) {
                // Mencegah navigasi default jika perlu
                e.preventDefault();

                // Mendapatkan file path dan nama file dari data attributes
                let filePath = downloadButton.getAttribute('data-file');
                let fileName = downloadButton.getAttribute('data-filename');

                if (filePath && filePath !== '') {
                    // Menampilkan SweetAlert2 loading modal
                    Swal.fire({
                        title: 'Memuat...',
                        text: 'Sedang memproses pengunduhan.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Mengunduh file menggunakan fetch dan blob
                    fetch(filePath)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('File tidak tersedia di situs');
                            }
                            return response.blob();
                        })
                        .then(blob => {
                            Swal.close(); // Menutup loading modal
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
                            Swal.close(); // Menutup loading modal
                            // Menampilkan SweetAlert2 error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'File tidak tersedia di situs.',
                                footer: 'Silakan coba lagi.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#2563EB'
                            });
                        });
                } else {
                    // Menampilkan SweetAlert2 error jika filePath tidak valid
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'File tidak tersedia untuk diunduh.',
                        footer: 'Silakan coba lagi.',
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

    // Ada bug yang menyebabkan centang pada semua checkbox tidak 
    // bisa dihilangkan saat tombol "Pilih Semua" dimatikan.
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
                // Jika ini adalah tombol "Pilih Semua"
                if (checkbox.classList.contains('active')) {
                    // Jika aktif, tampilkan ikon cek
                    const img = document.createElement('img');
                    img.classList.add('checkbox-icon', 'select-all'); // Tambahkan kelas 'select-all'
                    img.setAttribute('alt', 'Checked');
                    img.setAttribute('src', 'assets/img/icon_check.svg');
                    img.setAttribute('data-indicator', checkbox.getAttribute('data-indicator'));
                    img.setAttribute('data-student', 'all');
                    img.setAttribute('data-value', '1');
                    img.style.display = 'inline-block';
                    checkbox.parentNode.replaceChild(img, checkbox);
                    addCheckboxIconListener(img); // Tambahkan listener kembali
                } else {
                    // Jika tidak aktif, sembunyikan ikon cek
                    const div = document.createElement('div');
                    div.classList.add('checkbox', 'select-all'); // Tambahkan kelas 'select-all'
                    div.setAttribute('data-indicator', checkbox.getAttribute('data-indicator'));
                    div.setAttribute('data-student', 'all');
                    div.setAttribute('data-value', '0');
                    const innerDiv = document.createElement('div');
                    innerDiv.classList.add('large-ico-check');
                    innerDiv.style.display = 'none'; // Sembunyikan ikon cek
                    div.appendChild(innerDiv);
                    div.style.display = 'inline-block';
                    checkbox.parentNode.replaceChild(div, checkbox);
                    addCheckboxListener(div); // Tambahkan listener kembali
                }
            } else {
                // Untuk checkbox individu
                if (checkbox.classList.contains('active')) {
                    // Jika aktif, tampilkan ikon cek
                    const img = document.createElement('img');
                    img.classList.add('checkbox-icon');
                    img.setAttribute('alt', 'Checked');
                    img.setAttribute('src', 'assets/img/icon_check.svg');
                    img.setAttribute('data-indicator', checkbox.getAttribute('data-indicator'));
                    img.setAttribute('data-student', checkbox.getAttribute('data-student'));
                    img.setAttribute('data-value', '1');
                    img.style.marginRight = '2rem';
                    img.style.display = 'inline-block';
                    checkbox.parentNode.replaceChild(img, checkbox);
                    addCheckboxIconListener(img); // Tambahkan listener kembali
                } else {
                    // Jika tidak aktif, sembunyikan ikon cek
                    const div = document.createElement('div');
                    div.classList.add('checkbox');
                    div.setAttribute('data-indicator', checkbox.getAttribute('data-indicator'));
                    div.setAttribute('data-student', checkbox.getAttribute('data-student'));
                    div.setAttribute('data-value', '0');
                    const innerDiv = document.createElement('div');
                    innerDiv.classList.add('large-ico-check');
                    div.appendChild(innerDiv);
                    div.style.marginRight = '2rem';
                    div.style.display = 'inline-block';
                    checkbox.parentNode.replaceChild(div, checkbox);
                    addCheckboxListener(div); // Tambahkan listener kembali
                }
            }
        }

        /**
         * Fungsi untuk menambahkan event listener pada checkbox-icon.
         * @param {HTMLElement} icon - Elemen ikon checkbox.
         */
        function addCheckboxIconListener(icon) {
            icon.addEventListener('click', function() {
                const indicator = icon.getAttribute('data-indicator');
                const student = icon.getAttribute('data-student');

                if (student === 'all') {
                    // Menangani klik pada tombol "Pilih Semua"
                    const isActive = icon.getAttribute('data-value') === '1';
                    const checkboxes = document.querySelectorAll('.checkbox[data-indicator="' + indicator + '"]');

                    if (isActive) {
                        // Jika "Pilih Semua" sedang aktif, nonaktifkan semua checkbox di baris
                        checkboxes.forEach(function(cb) {
                            cb.classList.remove('active');
                            cb.setAttribute('data-value', '0');
                            cb.removeAttribute('data-disabled'); // Mengizinkan checkbox diubah
                            updateCheckboxIcon(cb);
                        });

                        // Ganti ikon "Pilih Semua" menjadi tidak dicentang dan pertahankan kelas 'select-all'
                        const div = document.createElement('div');
                        div.classList.add('checkbox', 'select-all'); // Memastikan kelas 'select-all' tetap
                        div.setAttribute('data-indicator', indicator);
                        div.setAttribute('data-student', 'all');
                        div.setAttribute('data-value', '0');
                        const innerDiv = document.createElement('div');
                        innerDiv.classList.add('large-ico-check');
                        innerDiv.style.display = 'none'; // Sembunyikan ikon cek saat off
                        div.appendChild(innerDiv);
                        div.style.display = 'inline-block';

                        // Ganti elemen dan tambahkan listener kembali
                        icon.parentNode.replaceChild(div, icon);
                        addCheckboxListener(div); // Memastikan listener terpasang pada elemen baru
                    } else {
                        // Jika "Pilih Semua" sedang tidak aktif, aktifkan semua checkbox di baris
                        checkboxes.forEach(function(cb) {
                            cb.classList.add('active');
                            cb.setAttribute('data-value', '1');
                            cb.setAttribute('data-disabled', 'true'); // Mengunci checkbox
                            updateCheckboxIcon(cb);
                        });

                        // Ganti ikon "Pilih Semua" menjadi dicentang dan pertahankan kelas 'select-all'
                        const img = document.createElement('img');
                        img.classList.add('checkbox-icon', 'select-all'); // Memastikan kelas 'select-all' tetap
                        img.setAttribute('alt', 'Checked');
                        img.setAttribute('src', 'assets/img/icon_check.svg');
                        img.setAttribute('data-indicator', indicator);
                        img.setAttribute('data-student', 'all');
                        img.setAttribute('data-value', '1');
                        img.style.display = 'inline-block';

                        // Ganti elemen dan tambahkan listener kembali
                        icon.parentNode.replaceChild(img, icon);
                        addCheckboxIconListener(img); // Memastikan listener terpasang pada elemen baru
                    }
                } else {
                    // Menangani klik pada checkbox individu
                    const isActive = icon.getAttribute('data-value') === '1';
                    if (isActive) {
                        // Nonaktifkan checkbox
                        const div = document.createElement('div');
                        div.classList.add('checkbox');
                        div.setAttribute('data-indicator', indicator);
                        div.setAttribute('data-student', student);
                        div.setAttribute('data-value', '0');
                        const innerDiv = document.createElement('div');
                        innerDiv.classList.add('large-ico-check');
                        div.appendChild(innerDiv);
                        div.style.marginRight = '2rem';
                        div.style.display = 'inline-block';
                        icon.parentNode.replaceChild(div, icon);
                        addCheckboxListener(div); // Memastikan listener terpasang pada elemen baru
                    } else {
                        // Aktifkan checkbox
                        const img = document.createElement('img');
                        img.classList.add('checkbox-icon');
                        img.setAttribute('alt', 'Checked');
                        img.setAttribute('src', 'assets/img/icon_check.svg');
                        img.setAttribute('data-indicator', indicator);
                        img.setAttribute('data-student', student);
                        img.setAttribute('data-value', '1');
                        img.style.marginRight = '2rem';
                        img.style.display = 'inline-block';
                        icon.parentNode.replaceChild(img, icon);
                        addCheckboxIconListener(img); // Memastikan listener terpasang pada elemen baru
                    }

                    // Perbarui status tombol "Pilih Semua"
                    updateSelectAllButton(indicator);

                    isFormDirty = true; // Menandai bahwa form telah diubah
                }
            });
        }

        /**
         * Fungsi untuk menambahkan event listener pada checkbox.
         * @param {HTMLElement} checkbox - Elemen checkbox.
         */
        function addCheckboxListener(checkbox) {
            checkbox.addEventListener('click', function() {
                const indicator = this.getAttribute('data-indicator');
                const student = this.getAttribute('data-student');

                if (student === 'all') {
                    // Menangani klik pada tombol "Pilih Semua" jika diwakili oleh div atau img
                    const isActive = this.getAttribute('data-value') === '1';
                    const checkboxes = document.querySelectorAll('.checkbox[data-indicator="' + indicator + '"]');

                    if (isActive) {
                        // Nonaktifkan semua checkbox di baris
                        checkboxes.forEach(function(cb) {
                            cb.classList.remove('active');
                            cb.setAttribute('data-value', '0');
                            cb.removeAttribute('data-disabled'); // Mengizinkan checkbox diubah
                            updateCheckboxIcon(cb);
                        });

                        // Ganti elemen "Pilih Semua" menjadi tidak dicentang
                        if (this.tagName.toLowerCase() === 'img') {
                            // Jika elemen adalah img
                            const div = document.createElement('div');
                            div.classList.add('checkbox', 'select-all'); // Memastikan kelas 'select-all' tetap
                            div.setAttribute('data-indicator', indicator);
                            div.setAttribute('data-student', 'all');
                            div.setAttribute('data-value', '0');
                            const innerDiv = document.createElement('div');
                            innerDiv.classList.add('large-ico-check');
                            innerDiv.style.display = 'none'; // Sembunyikan ikon cek saat off
                            div.appendChild(innerDiv);
                            div.style.display = 'inline-block';

                            // Ganti elemen dan tambahkan listener kembali
                            this.parentNode.replaceChild(div, this);
                            addCheckboxListener(div); // Memastikan listener terpasang pada elemen baru
                        } else {
                            // Jika elemen adalah div
                            const innerDiv = this.querySelector('.large-ico-check');
                            if (innerDiv) {
                                innerDiv.style.display = 'none'; // Sembunyikan ikon cek saat off
                            }
                            this.setAttribute('data-value', '0');
                        }
                    } else {
                        // Aktifkan semua checkbox di baris
                        checkboxes.forEach(function(cb) {
                            cb.classList.add('active');
                            cb.setAttribute('data-value', '1');
                            cb.setAttribute('data-disabled', 'true'); // Mengunci checkbox
                            updateCheckboxIcon(cb);
                        });

                        // Ganti elemen "Pilih Semua" menjadi dicentang
                        if (this.tagName.toLowerCase() === 'div') {
                            // Jika elemen adalah div
                            const img = document.createElement('img');
                            img.classList.add('checkbox-icon', 'select-all'); // Memastikan kelas 'select-all' tetap
                            img.setAttribute('alt', 'Checked');
                            img.setAttribute('src', 'assets/img/icon_check.svg');
                            img.setAttribute('data-indicator', indicator);
                            img.setAttribute('data-student', 'all');
                            img.setAttribute('data-value', '1');
                            img.style.display = 'inline-block';

                            // Ganti elemen dan tambahkan listener kembali
                            this.parentNode.replaceChild(img, this);
                            addCheckboxIconListener(img); // Memastikan listener terpasang pada elemen baru
                        } else {
                            // Jika elemen adalah img
                            this.setAttribute('data-value', '1');
                            this.setAttribute('src', 'assets/img/icon_check.svg'); // Pastikan ikon cek ditampilkan
                        }
                    }

                    isFormDirty = true; // Menandai bahwa form telah diubah
                } else {
                    // Menangani klik pada checkbox individu
                    const isActive = this.getAttribute('data-value') === '1';
                    if (isActive) {
                        // Nonaktifkan checkbox
                        const div = document.createElement('div');
                        div.classList.add('checkbox');
                        div.setAttribute('data-indicator', indicator);
                        div.setAttribute('data-student', student);
                        div.setAttribute('data-value', '0');
                        const innerDiv = document.createElement('div');
                        innerDiv.classList.add('large-ico-check');
                        div.appendChild(innerDiv);
                        div.style.marginRight = '2rem';
                        div.style.display = 'inline-block';
                        this.parentNode.replaceChild(div, this);
                        addCheckboxListener(div); // Memastikan listener terpasang pada elemen baru
                    } else {
                        // Aktifkan checkbox
                        const img = document.createElement('img');
                        img.classList.add('checkbox-icon');
                        img.setAttribute('alt', 'Checked');
                        img.setAttribute('src', 'assets/img/icon_check.svg');
                        img.setAttribute('data-indicator', indicator);
                        img.setAttribute('data-student', student);
                        img.setAttribute('data-value', '1');
                        img.style.marginRight = '2rem';
                        img.style.display = 'inline-block';
                        this.parentNode.replaceChild(img, this);
                        addCheckboxIconListener(img); // Memastikan listener terpasang pada elemen baru
                    }

                    // Perbarui status tombol "Pilih Semua"
                    updateSelectAllButton(indicator);

                    isFormDirty = true; // Menandai bahwa form telah diubah
                }
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
            const selectAllButton = document.querySelector('.checkbox.select-all[data-indicator="' + indicator + '"]');
            if (!selectAllButton) return;

            const checkboxes = document.querySelectorAll('.checkbox[data-indicator="' + indicator + '"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.classList.contains('active'));

            if (allChecked) {
                // Aktifkan "Pilih Semua"
                selectAllButton.classList.add('active');
                selectAllButton.setAttribute('data-value', '1');
            } else {
                // Nonaktifkan "Pilih Semua"
                selectAllButton.classList.remove('active');
                selectAllButton.setAttribute('data-value', '0');
            }

            // Perbarui ikon "Pilih Semua"
            updateCheckboxIcon(selectAllButton);
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