<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/buatkelas2.css" />
</head>

<body>
    <!-- Flashdata Messages sebagai Atribut Data -->
    <?php
    // Mendapatkan flashdata dari session
    $success_message = $this->session->flashdata('success');
    $error_message = $this->session->flashdata('error');
    ?>
    <div id="flashdata"
        data-success="<?= isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-error="<?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <div class="buat-kelas-2 unselectable-text">
        <!-- Sidebar -->
        <div class="sidebar">
            <a class="logo-ls-1-parent link" href="sidebarBeranda">
                <img oncontextmenu="return false;" class="logo-ls-1" alt="" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="lesson-study">edvisor</b>
            </a>
            <a class="profile-side-bar link" href="sidebarProfile">
                <div class="profile-side-bar-child" alt="">
                    <img oncontextmenu="return false;" class="profile-photo"
                        src="<?php echo !empty($user->src_profile_photo) ? $user->src_profile_photo : 'assets/default/default_profile_picture.jpg'; ?>"
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

        <!-- Navigasi Kembali -->
        <a class="buat-kelas-group link" href="sidebarGuruModel">
            <img oncontextmenu="return false;" class="iconsolidarrow-left" alt="" src="assets/img/icon_arrow_left.svg">
            <div class="buat-kelas2">Buat Kelas</div>
        </a>

        <!-- Display Tanggal dan Waktu -->
        <div class="date-container">
            <p id="dateDisplay" class="date"></p>
            <p id="timeDisplay" class="date"></p>
        </div>

        <!-- Konten Utama -->
        <div class="buat-kelas-parent">
            <div class="buat-kelas">Buat Kelas</div>
            <div class="detail-kelas-parent">
                <div class="detail-kelas">
                    <ol class="detail-kelas1">
                        <img oncontextmenu="return false;" class="group-icon" alt="" src="assets/img/icon_complete.svg">
                        <li>&ensp; &emsp; Detail Kelas</li>
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

            <!-- Form Unggah Berkas -->
            <form action="formUnggahBerkas" method="post" enctype="multipart/form-data" id="uploadForm">
                <div class="button-parent">
                    <!-- Upload Data Siswa -->
                    <div class="upload-document">
                        <div class="text-label">Data Siswa</div>
                        <div class="group-parent" id="dropDataSiswa">
                            <img oncontextmenu="return false;" class="iconsoliddocument-remove" id="statusDokumenSiswa"
                                alt="" src="assets/img/icon_upload_default.svg">
                            <div class="drag-drop-container" id="unggahDataSiswa">
                                <span class="drag-drop">Drag & Drop atau </span>
                                <span class="pilih-berkas">
                                    <span class="pilih-berkas1">Pilih Berkas</span>
                                </span>
                                <span class="untuk-mengunggah">
                                    <span class="untuk-mengunggah1">Untuk Mengunggah</span>
                                </span>
                            </div>
                            <div class="berkas-data-siswaxls" id="formatUnggahBerkasSiswa">Berkas Data Siswa
                                (*.xls, *.xlsx, *.csv) [Ukuran Maksimal 10MB]
                            </div>
                            <div class="frame-child" id="backgroundProcessBarSiswa"></div>
                            <div class="frame-item" id="progressProcessBarSiswa"></div>
                            <!-- Menggunakan ID yang sama dan menampilkan elemen sesuai status -->
                            <img oncontextmenu="return false;" class="iconsolidx-circle" id="uploadStatusSiswa" alt=""
                                src="assets/img/icon_upload_cancel.svg" style="display: none; visibility: hidden;">
                        </div>
                    </div>
                    <div class="text-button" id="unduhFormatDataSiswa">
                        <div class="div">*</div>
                        <div class="ini-adalah-text">Unduh Format Data Siswa Disini</div>
                    </div>

                    <!-- Upload Modul Ajar -->
                    <div class="upload-document1">
                        <div class="text-label">Modul Ajar</div>
                        <div class="group-parent" id="dropModulAjar">
                            <img oncontextmenu="return false;" class="iconsoliddocument-remove" id="statusDokumenModul" alt=""
                                src="assets/img/icon_upload_default.svg">
                            <div class="drag-drop-container" id="unggahModulAjar">
                                <span class="drag-drop">Drag & Drop atau </span>
                                <span class="pilih-berkas">
                                    <span class="pilih-berkas1">Pilih Berkas</span>
                                </span>
                                <span class="untuk-mengunggah">
                                    <span class="untuk-mengunggah1">Untuk Mengunggah</span>
                                </span>
                            </div>
                            <div class="berkas-data-siswaxls" id="formatUnggahBerkasModul">Berkas Modul Ajar (*.pdf)
                                [Ukuran Maksimal 10MB]
                            </div>
                            <div class="frame-child" id="backgroundProcessBarModul"></div>
                            <div class="frame-item" id="progressProcessBarModul"></div>
                            <!-- Menggunakan ID yang sama dan menampilkan elemen sesuai status -->
                            <img oncontextmenu="return false;" class="iconsolidx-circle" id="uploadStatusModul" alt=""
                                src="assets/img/icon_upload_cancel.svg" style="display: none; visibility: hidden;">
                        </div>
                    </div>

                    <!-- Upload Media Pembelajaran -->
                    <div class="upload-document2">
                        <div class="text-label">Media Pembelajaran</div>
                        <div class="group-parent" id="dropMediaPembelajaran">
                            <img oncontextmenu="return false;" class="iconsoliddocument-remove" id="statusDokumenMedia" alt=""
                                src="assets/img/icon_upload_default.svg">
                            <div class="drag-drop-container" id="unggahMediaPembelajaran">
                                <span class="drag-drop">Drag & Drop atau </span>
                                <span class="pilih-berkas">
                                    <span class="pilih-berkas1">Pilih Berkas</span>
                                </span>
                                <span class="untuk-mengunggah">
                                    <span class="untuk-mengunggah1">Untuk Mengunggah</span>
                                </span>
                            </div>
                            <div class="berkas-data-siswaxls" id="formatUnggahBerkasMedia">Berkas Media Pembelajaran
                                (*.pdf) [Ukuran Maksimal 10MB]
                            </div>
                            <div class="frame-child" id="backgroundProcessBarMedia"></div>
                            <div class="frame-item" id="progressProcessBarMedia"></div>
                            <!-- Menggunakan ID yang sama dan menampilkan elemen sesuai status -->
                            <img oncontextmenu="return false;" class="iconsolidx-circle" id="uploadStatusMedia" alt=""
                                src="assets/img/icon_upload_cancel.svg" style="display: none; visibility: hidden;">
                        </div>
                    </div>

                    <!-- Tombol Navigasi -->
                    <a class="sebelumnya-wrapper link" id="formSebelumnya" href="pageBuatKelas_detailKelas">
                        <div class="sebelumnya">Sebelumnya</div>
                    </a>
                    <button type="button" class="button link" id="Selanjutnya" disabled>
                        <div class="button1">Selanjutnya</div>
                    </button>
            </form>
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

    // Fungsi untuk memperbarui tanggal dan waktu setiap detik
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

    document.addEventListener("DOMContentLoaded", function() {
        const email = "<?php echo $user->email_address; ?>"; // Mendapatkan email pengguna dari PHP

        // Objek untuk menangani upload
        const elements = {
            dataSiswa: {
                drop: document.getElementById('dropDataSiswa'),
                upload: document.getElementById('unggahDataSiswa'),
                status: document.getElementById('statusDokumenSiswa'),
                progressBar: document.getElementById('progressProcessBarSiswa'),
                backgroundBar: document.getElementById('backgroundProcessBarSiswa'),
                statusIcon: document.getElementById('uploadStatusSiswa'),
                formatInfo: document.getElementById('formatUnggahBerkasSiswa'),
                defaultFormatText: 'Berkas Data Siswa(*.xls, *.xlsx, *.csv) [Ukuran Maksimal 10MB]',
                acceptedFormats: ['xls', 'xlsx', 'csv'],
                type: 'DataSiswa'
            },
            modulAjar: {
                drop: document.getElementById('dropModulAjar'),
                upload: document.getElementById('unggahModulAjar'),
                status: document.getElementById('statusDokumenModul'),
                progressBar: document.getElementById('progressProcessBarModul'),
                backgroundBar: document.getElementById('backgroundProcessBarModul'),
                statusIcon: document.getElementById('uploadStatusModul'),
                formatInfo: document.getElementById('formatUnggahBerkasModul'),
                defaultFormatText: 'Berkas Modul Ajar(*.pdf) [Ukuran Maksimal 10MB]',
                acceptedFormats: ['pdf'],
                type: 'ModulAjar'
            },
            mediaPembelajaran: {
                drop: document.getElementById('dropMediaPembelajaran'),
                upload: document.getElementById('unggahMediaPembelajaran'),
                status: document.getElementById('statusDokumenMedia'),
                progressBar: document.getElementById('progressProcessBarMedia'),
                backgroundBar: document.getElementById('backgroundProcessBarMedia'),
                statusIcon: document.getElementById('uploadStatusMedia'),
                formatInfo: document.getElementById('formatUnggahBerkasMedia'),
                defaultFormatText: 'Berkas Media Pembelajaran(*.pdf) [Ukuran Maksimal 10MB]',
                acceptedFormats: ['pdf'],
                type: 'MediaPembelajaran'
            }
        };

        // Fungsi untuk mengubah byte menjadi format yang mudah dibaca
        /**
         * Mengubah jumlah byte menjadi format yang lebih mudah dibaca (KB, MB, dll).
         * @param {number} bytes - Jumlah byte yang akan diubah.
         * @param {number} decimals - Jumlah desimal yang diinginkan.
         * @returns {string} - String yang merepresentasikan ukuran dalam format yang lebih mudah dibaca.
         */
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024,
                dm = decimals < 0 ? 0 : decimals,
                sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'],
                i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // Fungsi untuk mereset UI upload
        /**
         * Mereset antarmuka pengguna (UI) upload ke keadaan awal.
         * @param {Object} element - Objek yang berisi referensi ke elemen-elemen terkait upload.
         */
        function resetUploadUI(element) {
            element.upload.innerHTML = '<span class="drag-drop">Drag & Drop atau </span><span class="pilih-berkas"><span class="pilih-berkas1">Pilih Berkas </span></span><span class="untuk-mengunggah"><span class="untuk-mengunggah1">Untuk Mengunggah</span></span>';
            element.formatInfo.innerHTML = element.defaultFormatText;
            element.status.src = "assets/img/icon_upload_default.svg";
            element.statusIcon.style.display = "none";
            element.statusIcon.style.visibility = "hidden";
            element.progressBar.style.width = "0rem"; // Reset lebar progress bar
            element.progressBar.style.display = "none";
            element.progressBar.style.visibility = "hidden";
            element.backgroundBar.style.display = "none";
            element.backgroundBar.style.visibility = "hidden";
            element.statusIcon.src = "assets/img/icon_upload_cancel.svg";
            // Update status file
            uploadedFiles[element.type] = false;
            checkAllUploads();

            // Menghapus upload yang sedang berlangsung
            if (element.xhr) {
                element.xhr.abort();
            }
        }

        // Fungsi untuk memeriksa apakah semua file telah diupload
        /**
         * Memeriksa apakah semua file yang diperlukan telah berhasil diupload.
         * Jika semua sudah diupload, tombol "Selanjutnya" diaktifkan.
         */
        function checkAllUploads() {
            const allUploaded = uploadedFiles.DataSiswa && uploadedFiles.ModulAjar && uploadedFiles.MediaPembelajaran;
            document.getElementById('Selanjutnya').disabled = !allUploaded;
        }

        // Objek untuk melacak status upload
        const uploadedFiles = {
            DataSiswa: false,
            ModulAjar: false,
            MediaPembelajaran: false
        };

        // Fungsi untuk menangani drop file
        /**
         * Menangani event drop pada area upload.
         * @param {Event} event - Event yang terjadi saat file dijatuhkan.
         * @param {Object} element - Objek yang berisi referensi ke elemen-elemen terkait upload.
         */
        function handleDrop(event, element) {
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            if (file) {
                processFile(file, element);
            }
        }

        // Fungsi untuk menangani upload melalui klik
        /**
         * Menangani proses upload ketika pengguna mengklik area upload.
         * Membuka dialog pemilihan file.
         * @param {Object} element - Objek yang berisi referensi ke elemen-elemen terkait upload.
         */
        function handleUploadClick(element) {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = element.acceptedFormats.map(ext => '.' + ext).join(',');

            fileInput.onchange = function() {
                const file = fileInput.files[0];
                if (file) {
                    processFile(file, element);
                }
            };

            fileInput.click();
        }

        // Fungsi untuk memproses file
        /**
         * Memproses file yang diupload, termasuk validasi dan memulai proses upload.
         * @param {File} file - File yang akan diproses.
         * @param {Object} element - Objek yang berisi referensi ke elemen-elemen terkait upload.
         */
        function processFile(file, element) {
            const fileExtension = file.name.split('.').pop().toLowerCase();

            // Validasi format file
            if (!element.acceptedFormats.includes(fileExtension)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format Tidak Sesuai',
                    text: `Format file harus ${element.acceptedFormats.map(ext => '.' + ext).join(', ')}`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563EB'
                });
                return;
            }

            // Validasi ukuran file (maksimal 10MB)
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran File Terlalu Besar',
                    text: 'Ukuran file maksimal 10MB.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563EB'
                });
                return;
            }

            // Mengupdate UI sebelum upload
            element.upload.innerText = file.name; // Menampilkan nama asli file
            element.formatInfo.innerText = `Ukuran file: ${formatBytes(file.size)} (${fileExtension.toUpperCase()})`;
            element.status.src = "assets/img/icon_upload_active.svg";
            element.backgroundBar.style.display = "block";
            element.backgroundBar.style.visibility = "visible";
            element.progressBar.style.display = "block";
            element.progressBar.style.visibility = "visible";
            element.statusIcon.style.display = "block";
            element.statusIcon.style.visibility = "visible";
            element.statusIcon.src = "assets/img/icon_upload_cancel.svg"; // Menampilkan ikon batal

            // Mulai animasi progress bar dari 1% hingga 80% dalam 3 detik
            let progress = 1;
            updateProgressBar(element.progressBar, progress);
            const progressInterval = setInterval(() => {
                if (progress < 80) {
                    progress += 1;
                    updateProgressBar(element.progressBar, progress);
                } else {
                    clearInterval(progressInterval);
                }
            }, 30); // 3 detik total

            // Melanjutkan upload dari 81% hingga 100% setelah 3 detik
            setTimeout(() => {
                uploadFile(file, element, progressInterval);
            }, 3000);

            // Menyimpan referensi upload yang sedang berlangsung
            element.isUploading = true;
        }

        // Fungsi untuk memperbarui lebar progress bar dalam rem
        /**
         * Memperbarui lebar progress bar berdasarkan persentase yang diberikan.
         * @param {HTMLElement} progressBar - Elemen progress bar yang akan diperbarui.
         * @param {number} percentage - Persentase lebar progress bar.
         */
        function updateProgressBar(progressBar, percentage) {
            const maxWidth = 32.25; // Maksimal 32.25rem
            const newWidth = (percentage / 100) * maxWidth;
            progressBar.style.width = newWidth + "rem";
        }

        // Fungsi untuk mengupload file ke server
        /**
         * Mengupload file ke server menggunakan XMLHttpRequest.
         * @param {File} file - File yang akan diupload.
         * @param {Object} element - Objek yang berisi referensi ke elemen-elemen terkait upload.
         * @param {number} progressInterval - ID interval untuk animasi progress bar.
         */
        function uploadFile(file, element, progressInterval) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', element.type);

            const xhr = new XMLHttpRequest();
            element.xhr = xhr; // Menyimpan referensi xhr untuk pembatalan

            xhr.open("POST", "formUnggahBerkas", true);

            // Update progress bar secara real-time dari 81% hingga 100%
            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    const percentComplete = Math.round((event.loaded / event.total) * 100);
                    const newProgress = 80 + (percentComplete * 0.2);
                    updateProgressBar(element.progressBar, newProgress);
                }
            };

            xhr.onload = function() {
                clearInterval(progressInterval);
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            // Tampilkan ikon sukses
                            element.statusIcon.src = "assets/img/icon_upload_success.svg";
                            // Perbarui status upload
                            uploadedFiles[element.type] = true;
                            checkAllUploads();
                            element.isUploading = false;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Upload Gagal',
                                text: response.message,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#2563EB'
                            });
                            resetUploadUI(element);
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Gagal',
                            text: 'Terjadi kesalahan saat memproses respons dari server.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2563EB'
                        });
                        // console.log(e);
                        resetUploadUI(element);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Gagal',
                        text: 'Terjadi kesalahan saat mengupload file.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#2563EB'
                    });
                    resetUploadUI(element);
                }
            };

            xhr.onerror = function() {
                clearInterval(progressInterval);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Terjadi kesalahan jaringan.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563EB'
                });
                resetUploadUI(element);
            };

            xhr.onabort = function() {
                // Handle ketika upload dibatalkan
                clearInterval(progressInterval);
                resetUploadUI(element);
            };

            xhr.send(formData);
        }

        // Fungsi untuk menetapkan event listeners
        /**
         * Menetapkan event listeners untuk setiap elemen upload yang ada.
         */
        for (const key in elements) {
            if (elements.hasOwnProperty(key)) {
                const element = elements[key];

                // Event dragover untuk mencegah default behavior
                element.drop.addEventListener('dragover', function(event) {
                    event.preventDefault();
                });

                // Event drop untuk menangani file yang dijatuhkan
                element.drop.addEventListener('drop', function(event) {
                    handleDrop(event, element);
                });

                // Event klik untuk membuka dialog file
                element.upload.addEventListener('click', function() {
                    handleUploadClick(element);
                });

                // Event klik pada ikon status untuk batal atau sukses
                element.statusIcon.addEventListener('click', function() {
                    if (element.isUploading) {
                        // Jika sedang mengupload, maka batalkan
                        resetUploadUI(element);
                    } else {
                        // Jika sudah selesai, tidak melakukan apa-apa atau mungkin menghapus file
                    }
                });
            }
        }

        // Event klik pada tombol "Unduh Format Data Siswa"
        document.getElementById('unduhFormatDataSiswa').addEventListener('click', function() {
            window.location.href = "assets/media/formatDataSiswa/FormatDataSiswa_beta.xlsx";
        });

        // Event klik pada tombol "Selanjutnya"
        document.getElementById('Selanjutnya').addEventListener('click', function() {
            // Pastikan semua file telah diupload sebelum melanjutkan
            if (document.getElementById('Selanjutnya').disabled === false) {
                // Simpan session atau lakukan tindakan lain jika diperlukan
                // Kemudian arahkan ke halaman berikutnya
                window.location.href = "pageBuatKelas_detailObserver";
            }
        });

        // Fungsi untuk memuat status upload dari session
        /**
         * Memuat status upload sebelumnya dari session dan memperbarui UI sesuai status tersebut.
         */
        function loadUploadedFiles() {
            // Mendapatkan data dari server-side melalui PHP
            const uploadedFilesSession = <?php echo json_encode($this->session->userdata('class_files')); ?>;

            if (uploadedFilesSession) {
                for (const key in elements) {
                    if (elements.hasOwnProperty(key)) {
                        const element = elements[key];
                        const type = element.type;
                        if (uploadedFilesSession[type]) {
                            // Update UI seolah-olah file telah diupload
                            element.upload.innerText = uploadedFilesSession["original_" + type];
                            element.formatInfo.innerText = `File telah diupload sebelumnya`;
                            element.status.src = "assets/img/icon_upload_active.svg";
                            element.backgroundBar.style.display = "block";
                            element.backgroundBar.style.visibility = "visible";
                            element.progressBar.style.display = "block";
                            element.progressBar.style.visibility = "visible";
                            updateProgressBar(element.progressBar, 100);
                            element.statusIcon.style.display = "block";
                            element.statusIcon.style.visibility = "visible";
                            element.statusIcon.src = "assets/img/icon_upload_success.svg";
                            uploadedFiles[type] = true;
                        }
                    }
                }
                checkAllUploads();
            }
        }
        // Memuat status upload saat halaman dimuat
        loadUploadedFiles();
    });

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

        // Fungsi untuk memeriksa apakah elemen benar-benar overflow
        /**
         * Memeriksa apakah sebuah elemen mengalami overflow.
         * @param {HTMLElement} element - Elemen yang akan diperiksa.
         * @returns {Object} - Objek yang berisi informasi tentang overflow.
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

        // Fungsi untuk menangani animasi scroll pada elemen tertentu
        /**
         * Menyiapkan animasi scroll untuk sebuah elemen jika terjadi overflow.
         * @param {HTMLElement} element - Elemen yang akan dianimasikan.
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

            // Fungsi untuk memulai animasi scroll
            /**
             * Memulai animasi scroll pada elemen.
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

                // Fungsi animasi menggunakan requestAnimationFrame
                /**
                 * Fungsi animasi yang akan dipanggil setiap frame.
                 * @param {number} timestamp - Waktu saat ini dalam milidetik.
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

            // Fungsi untuk menghentikan animasi scroll dan mengembalikan posisi awal
            /**
             * Menghentikan animasi scroll pada elemen dan mengembalikannya ke teks asli.
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
</script>

</html>