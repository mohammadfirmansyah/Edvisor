<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <base href="<?php echo base_url(); ?>">
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/kelasobserver3.css" />
</head>

<body>
    <!-- Flashdata Messages sebagai Data Attributes -->
    <?php
    // Mendapatkan pesan sukses dan error dari session flashdata
    $success_message = $this->session->flashdata('success');
    $error_message = $this->session->flashdata('error');
    ?>
    <!-- Menyimpan pesan flashdata dalam elemen tersembunyi dengan data attributes -->
    <div id="flashdata"
        data-success="<?= isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-error="<?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <div class="observasi-2 unselectable-text">
        <!-- Sidebar Navigasi -->
        <div class="sidebar">
            <!-- Logo dan Navigasi Utama -->
            <a class="logo-ls-1-parent link" href="sidebarBeranda">
                <!-- Menampilkan logo Lesson Study dengan pencegahan context menu -->
                <img oncontextmenu="return false;" class="logo-ls-1" alt="Logo Lesson Study" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </a>
            <!-- Profil Pengguna -->
            <a class="profile-side-bar link" href="sidebarProfile">
                <div class="profile-side-bar-child" alt="Foto Profil">
                    <!-- Menampilkan foto profil pengguna dengan fallback ke gambar default -->
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
                <!-- Link ke Beranda -->
                <a class="item-side-bar-default link" href="sidebarBeranda">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Beranda" src="assets/img/icon_beranda.svg">
                    <div class="text-sidebar-default">Beranda</div>
                </a>
                <!-- Link ke Guru Model -->
                <a class="item-side-bar-default link" href="sidebarGuruModel">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Guru Model" src="assets/img/icon_guru_model.svg">
                    <div class="text-sidebar-default">Guru Model</div>
                </a>
                <!-- Link aktif ke Observer -->
                <a class="item-side-bar-active link" href="sidebarObserver">
                    <img oncontextmenu="return false;" class="icon-sidebar-active" alt="Observer" src="assets/img/icon_observer.svg">
                    <div class="text-sidebar-active">Observer</div>
                </a>
                <!-- Link ke Bantuan -->
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
        <!-- Container Utama Kelas dan Formulir -->
        <div class="menu-catatan-aktivitas-siswa-parent">
            <!-- Header Kelas -->
            <div class="card-header">
                <!-- Menampilkan Nama Kelas dengan sanitasi -->
                <div class="xi-rekayasa-perangkat"><?= htmlspecialchars($class->class_name, ENT_QUOTES, 'UTF-8'); ?></div>
                <!-- Menampilkan Subjek dan Jumlah Siswa -->
                <div class="dasar-pemrograman">
                    <span class="dasar-pemrograman1"><?= htmlspecialchars($class->subject, ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="dasar-pemrograman2"> | <?= htmlspecialchars($class->number_of_students, ENT_QUOTES, 'UTF-8'); ?> Siswa</span>
                </div>
                <!-- Kompetensi Dasar -->
                <div class="kompetensi-dasar-menerapkan-container">
                    <span class="kompetensi-dasar">Kompetensi Dasar: </span>
                    <b><?= htmlspecialchars($class->basic_competency, ENT_QUOTES, 'UTF-8'); ?></b>
                    <span> </span>
                </div>
                <!-- Informasi Tanggal dan Waktu -->
                <div class="group-parent">
                    <!-- Menampilkan Tanggal dengan format Indonesia -->
                    <div class="frame-wrapper">
                        <div class="date-range-parent">
                            <img oncontextmenu="return false;" class="iconoutlineinformation-circl" alt="Tanggal"
                                src="assets/img/icon_calendar.svg">
                            <div class="nomor-siswa">
                                <?= htmlspecialchars(indonesian_date($class->date), ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    </div>
                    <!-- Menampilkan Waktu Mulai dan Selesai -->
                    <div class="alarm-parent">
                        <img oncontextmenu="return false;" class="iconoutlineinformation-circl" alt="Waktu" src="assets/img/icon_alarm.svg">
                        <div class="nomor-siswa">
                            <?= date('H:i', strtotime($class->start_time)) . ' - ' . date('H:i', strtotime($class->end_time)); ?>
                        </div>
                    </div>
                </div>
                <!-- Informasi Guru Model -->
                <div class="guru-model-parent">
                    <div class="guru-model">Guru Model : </div>
                    <!-- Menampilkan foto Guru Model dengan fallback -->
                    <img oncontextmenu="return false;" class="group-child"
                        src="<?php echo !empty($class->guru_model_src_profile_photo) ? base_url($class->guru_model_src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>"
                        alt="Foto Guru Model">
                    <!-- Menampilkan Nama Guru Model dengan sanitasi -->
                    <div class="courtney-henry"><?= htmlspecialchars($class->guru_model_name, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
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
                                echo '<img oncontextmenu="return false;" class="frame-item" src="' .
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
            </div>
            <!-- Berkas yang Dibutuhkan -->
            <div class="berkas-yang-dibutuhkan-parent">
                <div class="berkas-yang-dibutuhkan">Berkas Yang Dibutuhkan</div>
                <div class="frame-inner"></div>
                <!-- Data Siswa -->
                <img oncontextmenu="return false;" class="iconsoliddocument-text" alt="Data Siswa" src="assets/img/icon_doc.svg">
                <div class="text-button">
                    <div class="ini-adalah-text">Data Siswa</div>
                </div>
                <!-- Tombol Download Data Siswa dengan Ekstensi Asli -->
                <div class="download-button"
                    data-file="<?= !empty($class->src_student_data_file) ? base_url($class->src_student_data_file) : ''; ?>"
                    data-filename="Data_Siswa.<?= !empty($class->student_file_ext) ? htmlspecialchars($class->student_file_ext, ENT_QUOTES, 'UTF-8') : 'csv'; ?>">
                    <div class="download-button-child"></div>
                    <img oncontextmenu="return false;" class="iconsoliddownload" alt="Download Data Siswa" src="assets/img/icon_download.svg">
                    <div class="frame-parent">
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
                <div class="download-button1"
                    data-file="<?= !empty($class->src_teaching_module_file) ? base_url($class->src_teaching_module_file) : ''; ?>"
                    data-filename="Modul_Ajar.<?= !empty($class->modul_file_ext) ? htmlspecialchars($class->modul_file_ext, ENT_QUOTES, 'UTF-8') : 'pdf'; ?>">
                    <div class="download-button-child"></div>
                    <img oncontextmenu="return false;" class="iconsoliddownload" alt="Download Modul Ajar" src="assets/img/icon_download.svg">
                    <div class="frame-parent">
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
                <div class="download-button2"
                    data-file="<?= !empty($class->src_learning_media_file) ? base_url($class->src_learning_media_file) : ''; ?>"
                    data-filename="Media_Pembelajaran.<?= !empty($class->media_file_ext) ? htmlspecialchars($class->media_file_ext, ENT_QUOTES, 'UTF-8') : 'pdf'; ?>">
                    <div class="download-button-child"></div>
                    <img oncontextmenu="return false;" class="iconsoliddownload" alt="Download Media Pembelajaran" src="assets/img/icon_download.svg">
                    <div class="frame-parent">
                        <div class="unduh-wrapper">
                            <div class="unduh">Unduh</div>
                        </div>
                        <img oncontextmenu="return false;" class="group-item" alt="" src="assets/img/polygon.svg">
                    </div>
                </div>
            </div>
            <!-- Menu Tab -->
            <div class="tab-menu">
                <!-- Link ke Penilaian Kegiatan Mengajar -->
                <a class="menu-2 link link-color-unset"
                    href="pageKelasObserver1/<?php echo $encrypted_class_id ?>">
                    <div class="penilaian-kegiatan-mengajar">Penilaian Kegiatan Mengajar</div>
                </a>
                <!-- Link ke Lembar Pengamatan Siswa -->
                <a class="menu-2 link link-color-unset"
                    href="pageKelasObserver2/<?php echo $encrypted_class_id ?>">
                    <div class="penilaian-kegiatan-mengajar">Lembar Pengamatan Siswa
                    </div>
                </a>
                <!-- Tab aktif untuk Catatan Aktivitas Siswa -->
                <div class="menu-1">
                    <div class="penilaian-kegiatan-mengajar">Catatan Aktivitas Siswa</div>
                </div>
            </div>
            <!-- Form Catatan Aktivitas Siswa -->
            <div class="menu-catatan-aktivitas-siswa">
                <!-- Petunjuk Pengisian Formulir -->
                <div class="petunjuk">
                    <div class="iconoutlineinformation-circl-parent">
                        <!-- Icon informasi -->
                        <img oncontextmenu="return false;" class="iconoutlineinformation-circl" alt="Informasi"
                            src="assets/img/icon_information.svg">
                        <div class="petunjuk1">Petunjuk</div>
                        <!-- Icon untuk menutup petunjuk -->
                        <img oncontextmenu="return false;" class="iconoutlineinformation-circl1" alt="Tutup" src="assets/img/icon_up.svg">
                    </div>
                    <div class="berilah-skor-pada-indikatoras-wrapper">
                        <!-- Petunjuk detail untuk pengisian form -->
                        <div class="berilah-skor-pada">Pilih satu siswa yang Anda amati dan menunjukkan gejala memiliki
                            kendala selama proses pembelajaran berlangsung. Kemudian catatlah informasi yang Anda
                            dapatkan berdasarkan pengamatan Anda terhadap siswa tersebut. Apabila ada lebih dari satu
                            siswa yang terlihat mengalami kendala, maka silahkan gandakan formulir ini sesuai jumlah yang
                            dibutuhkan. Apabila tidak ada siswa yang terlihat memiliki kendala, tetap pilih salah satu
                            dari sekian siswa yang Anda amati untuk mendapat informasi lebih spesifik.</div>
                    </div>
                </div>
                <!-- Formulir Catatan Aktivitas Siswa -->
                <form id="activityNoteForm" action="formCatatanAktivitasSiswa" method="POST">
                    <!-- Menyertakan ID Kelas dan ID Observer sebagai input tersembunyi -->
                    <input type="hidden" name="class_id"
                        value="<?php echo htmlspecialchars($encrypted_class_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="observer_user_id"
                        value="<?php echo htmlspecialchars($this->session->userdata('user_id'), ENT_QUOTES, 'UTF-8'); ?>">
                    <!-- Menambahkan Input Tersembunyi untuk Menandai Perubahan Tanda Tangan -->
                    <input type="hidden" name="signatureChanged" id="signatureChanged" value="0">
                    <!-- Daftar Pertanyaan -->
                    <div class="text-area-parent">
                        <?php
                        // Daftar pertanyaan yang akan ditampilkan
                        $questions = [
                            "Bagaimana aktivitas siswa ketika awal pembelajaran (apersepsi, motivasi, dan pengantar awal pembelajaran)?",
                            "Bagaimana aktivitas siswa ketika pelaksanaan pembelajaran (penerapan metode, model, atau media pembelajaran)?",
                            "Bagaimana aktivitas siswa saat berinteraksi dengan guru dan siswa lain?",
                            "Bagaimana tanggapan siswa terhadap segala aktivitas yang tidak mendukung pelajaran?",
                            "Secara khusus, pengalaman berharga apa yang dapat Anda peroleh dari kegiatan pengamatan terhadap siswa tersebut dalam kegiatan pembelajaran ini?"
                        ];
                        foreach ($questions as $index => $question) {
                            // Mengambil jawaban sebelumnya jika ada, dan menggantinya dengan string kosong jika null
                            $answer = isset($activity_note->{'answer_question' . ($index + 1)}) ? htmlspecialchars($activity_note->{'answer_question' . ($index + 1)}, ENT_QUOTES, 'UTF-8') : '';
                            echo '<div class="text-area">';
                            echo '<div class="label">';
                            // Menampilkan nomor pertanyaan
                            echo '<ol class="bagaimana-aktivitas-siswa-keti" start="' . ($index + 1) . '">';
                            echo '<li>' . htmlspecialchars($question, ENT_QUOTES, 'UTF-8') . '</li>';
                            echo '</ol>';
                            echo '</div>';
                            echo '<div class="text-area-child">';
                            // Menampilkan textarea untuk jawaban
                            echo '<textarea name="answer_question' . ($index + 1) . '" id="answer_question' . ($index + 1) . '">' . $answer . '</textarea>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <!-- Tanda Tangan -->
                    <div class="tanda-tangan">
                        <div class="tanda-tangan1">Tanda Tangan</div>
                        <div class="line-parent">
                            <!-- Kanvas untuk menggambar tanda tangan -->
                            <canvas id="signatureCanvas" width="500" height="500"></canvas>
                            <!-- Input tersembunyi untuk menyimpan gambar tanda tangan -->
                            <input id="signatureImage" type="hidden" name="src_signature_file"
                                value="<?= isset($activity_note) && isset($activity_note->src_signature_file) ? htmlspecialchars($activity_note->src_signature_file, ENT_QUOTES, 'UTF-8') : ''; ?>">
                            <!-- Placeholder teks saat belum ada tanda tangan -->
                            <div id="placeholderCanvas" class="buat-tanda-tangan">Buat Tanda Tangan Di Sini</div>
                            <!-- Tombol untuk menghapus tanda tangan -->
                            <img oncontextmenu="return false;" id="deleteSignature" class="delete-button-icon" alt="Hapus Tanda Tangan"
                                src="<?= isset($activity_note) && !empty($activity_note->src_signature_file) ? 'assets/img/icon_delete_active.svg' : 'assets/img/icon_delete_inactive.svg'; ?>">
                        </div>
                        <!-- Tombol Simpan Formulir -->
                        <button type="submit" class="button link" id="saveForm">
                            <div class="button1">Simpan</div>
                        </button>
                    </div>
                </form>
            </div>
            <div class="observasi-2-child">
                <!-- Elemen kosong, mungkin untuk styling tambahan -->
            </div>
        </div>
</body>

<script> 
    // Fungsi untuk menampilkan SweetAlert berdasarkan flashdata
    document.addEventListener("DOMContentLoaded", function() {
        // Mengambil elemen flashdata
        var flashdata = document.getElementById('flashdata');
        var success = flashdata.getAttribute('data-success');
        var error = flashdata.getAttribute('data-error');

        // Menampilkan SweetAlert untuk pesan sukses jika ada
        if (success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: success,
                confirmButtonText: 'OK',
                confirmButtonColor: '#2563EB'
            });
        }

        // Menampilkan SweetAlert untuk pesan error jika ada
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

        // Mengubah format tanggal dan waktu sesuai locale Indonesia
        const dateString = now.toLocaleDateString('id-ID', optionsDate);
        const timeString = now.toLocaleTimeString('id-ID', optionsTime);

        // Menampilkan tanggal dan waktu pada elemen yang ditentukan
        document.getElementById('dateDisplay').innerText = dateString;
        document.getElementById('timeDisplay').innerText = timeString;
    }

    // Memperbarui tanggal dan waktu setiap detik
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Fungsi untuk menangani fokus dan hover pada textarea
    document.addEventListener('DOMContentLoaded', function() {
        let isHovering = false;
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

            // Menandai bahwa form telah diubah saat ada input di textarea
            textarea.addEventListener('input', function() {
                isFormDirty = true;
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
         * Fungsi untuk mengatur ukuran canvas sesuai dengan CSS dan DPR (Device Pixel Ratio)
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
         * Fungsi untuk memulai menggambar
         * @param {Event} event - Event mouse down
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
         * Fungsi untuk menggambar di canvas
         * @param {Event} event - Event mouse move
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
         * Fungsi untuk berhenti menggambar
         */
        function stopDrawing() {
            if (isDrawing) {
                isDrawing = false;
                updateSignatureImage();
            }
        }

        /**
         * Fungsi untuk memperbarui gambar tanda tangan ke dalam input (PNG)
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
         * Fungsi untuk memproses tanda tangan: cropping dan scaling
         * @returns {Promise<string>} - Data URL dari tanda tangan yang diproses
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
         * Fungsi untuk mengecek apakah canvas kosong
         * @returns {boolean} - True jika canvas kosong, false jika tidak
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
         * Fungsi untuk menyembunyikan placeholder jika sudah ada tanda tangan
         */
        function hidePlaceholder() {
            if (!isCanvasBlank()) {
                placeholderCanvas.style.display = 'none';
            }
        }

        /**
         * Fungsi untuk menampilkan placeholder jika belum ada tanda tangan
         */
        function showPlaceholder() {
            if (isCanvasBlank()) {
                placeholderCanvas.style.display = 'block';
            }
        }

        /**
         * Fungsi untuk memperbarui status tombol delete berdasarkan keberadaan tanda tangan
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
         * Fungsi untuk menghapus tanda tangan dengan konfirmasi
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
                    // Menghapus gambar pada canvas
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
         * Fungsi untuk mendapatkan posisi mouse relatif terhadap canvas
         * @param {HTMLCanvasElement} canvas - Elemen canvas
         * @param {Event} event - Event mouse
         * @returns {Object} - Objek dengan properti x dan y
         */
        function getMousePos(canvas, event) {
            const rect = canvas.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            return {
                x: (event.clientX - rect.left) / rect.width * canvas.width / dpr,
                y: (event.clientY - rect.top) / rect.height * canvas.height / dpr
            };
        }

        // Event listener untuk tombol hapus tanda tangan
        deleteSignatureButton.addEventListener('click', function() {
            if (hasSignature && signatureImage.value) {
                clearCanvas();
            }
        });

        // Mengubah icon tombol saat di hover
        deleteSignatureButton.addEventListener('mouseover', function() {
            if (hasSignature && signatureImage.value) {
                deleteSignatureButton.src = 'assets/img/icon_delete_hover.svg';
            }
        });

        deleteSignatureButton.addEventListener('mouseout', function() {
            updateDeleteButtonState();
        });

        // Event listener untuk menggambar pada canvas
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

    // Fungsi untuk menangani petunjuk
    document.addEventListener('DOMContentLoaded', function() {
        const petunjuk = document.querySelector('.petunjuk');
        const iconParent = petunjuk.querySelector('.iconoutlineinformation-circl-parent');

        // Menambahkan event listener untuk klik pada tombol petunjuk
        iconParent.addEventListener('click', function() {
            // Toggle kelas 'active' pada elemen petunjuk untuk menampilkan atau menyembunyikan
            petunjuk.classList.toggle('active');
        });
    });

    // Fungsi untuk menangani tombol download
    document.addEventListener('DOMContentLoaded', function() {
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

            // Event saat mouse masuk ke ikon download
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

            // Event saat mouse keluar dari ikon download
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

            // Menambahkan event click untuk tombol download
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

    // Fungsi untuk animasi teks berjalan jika overflow
    window.onload = function() {
        const targetPairs = [];

        // Daftar kelas langsung yang akan dianimasikan
        const directClassesToAnimate = [
            "nama-pengguna",
            "email-pengguna",
            "xi-rekayasa-perangkat",
            "dasar-pemrograman1",
            "kompetensi-dasar-menerapkan-container",
            "courtney-henry"
        ];

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
                 * @param {DOMHighResTimeStamp} timestamp - Waktu saat frame dimulai
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
            const elements = document.querySelectorAll(`.${className}`);

            elements.forEach(element => {
                setupScrollAnimation(element);
                elementsToAnimate.push({
                    element: element,
                    type: 'direct'
                });

                const overflowInfo = isElementOverflowing(element);
                if (overflowInfo.overflowing) {
                    // Menambahkan event listener untuk memulai animasi saat mouse masuk
                    element.addEventListener("mouseenter", function() {
                        element.startScroll();
                    });

                    // Menambahkan event listener untuk menghentikan animasi saat mouse keluar
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

    // Variabel untuk menandai apakah form telah diubah
    let isFormDirty = false;

    // Menangani konfirmasi saat berpindah halaman tanpa menyimpan
    document.addEventListener('DOMContentLoaded', function() {
        const saveFormButton = document.getElementById('saveForm');
        const activityNoteForm = document.getElementById('activityNoteForm');

        // Menangani submit form dengan SweetAlert
        activityNoteForm.addEventListener('submit', function(e) {
            // Tidak memblokir submit form agar memungkinkan penyimpanan meskipun textarea kosong
            // Mengatur isFormDirty ke false setelah submit
            isFormDirty = false;
        });

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
                            // Jika pengguna memilih untuk menyimpan, submit form dan navigasi setelahnya
                            // Menambahkan event listener sementara untuk submit
                            activityNoteForm.addEventListener('submit', function onSubmit() {
                                // Setelah submit, navigasi ke href
                                window.location.href = href;
                                // Menghapus event listener setelah navigasi
                                activityNoteForm.removeEventListener('submit', onSubmit);
                            });
                            // Submit form
                            activityNoteForm.submit();
                        } else if (result.isDenied) {
                            // Jika pengguna memilih untuk meninggalkan halaman, navigasi ke href
                            window.location.href = href;
                        }
                        // Jika pengguna memilih 'Batal', tidak melakukan apa-apa
                    });
                }
            });
        });

        // Menangani event perubahan pada form untuk menandai form sebagai dirty
        activityNoteForm.addEventListener('change', function() {
            isFormDirty = true;
        });
    });
</script>

</html>