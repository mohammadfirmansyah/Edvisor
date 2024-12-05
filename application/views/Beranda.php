<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/beranda.css" />
</head>

<body>
    <?php
    // Mendapatkan flashdata yang akan digunakan untuk menampilkan pesan
    $success_message = isset($success_message) ? $success_message : '';
    $error_message = isset($error_message) ? $error_message : '';
    $warning_message = isset($warning_message) ? $warning_message : '';
    $notice_message = isset($notice_message) ? $notice_message : '';
    $deprecated_message = isset($deprecated_message) ? $deprecated_message : '';
    $class_not_started = isset($class_not_started) ? $class_not_started : '';
    $error_long = isset($error_long) ? $error_long : '';
    $success_login = isset($success_login) ? $success_login : '';
    $already_logged_in = isset($already_logged_in) ? $already_logged_in : ''; // Menambahkan ini
    ?>
    <!-- Menyimpan flashdata sebagai atribut data pada div dengan id 'flashdata' -->
    <div id="flashdata"
        data-success="<?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-error="<?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-warning="<?= htmlspecialchars($warning_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-notice="<?= htmlspecialchars($notice_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-deprecated="<?= htmlspecialchars($deprecated_message, ENT_QUOTES, 'UTF-8'); ?>"
        data-class-not-started="<?= htmlspecialchars($class_not_started, ENT_QUOTES, 'UTF-8'); ?>"
        data-error-long="<?= htmlspecialchars($error_long, ENT_QUOTES, 'UTF-8'); ?>"
        data-success-login="<?= htmlspecialchars($success_login, ENT_QUOTES, 'UTF-8'); ?>"
        data-already-logged-in="<?= htmlspecialchars($already_logged_in, ENT_QUOTES, 'UTF-8'); ?>"> <!-- Menambahkan ini -->
    </div>

    <div class="home content unselectable-text">
        <!-- Bagian sidebar -->
        <div class="sidebar">
            <!-- Logo Lesson Study yang mengarah ke halaman Beranda sidebar -->
            <a class="logo-ls-1-parent link" href="sidebarBeranda">
                <img oncontextmenu="return false;" class="logo-ls-1" alt="Logo Lesson Study" src="assets/img/logo_lesson_study_sidebar.svg">
                <b class="cari-disini">edvisor</b>
            </a>
            <!-- Link profil pengguna di sidebar -->
            <a class="profile-side-bar link" href="sidebarProfile">
                <div class="profile-side-bar-child" alt="Foto Profil">
                    <!-- Foto profil pengguna, default jika tidak ada -->
                    <img oncontextmenu="return false;" class="profile-photo" src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>" alt="Foto Profil">
                </div>
                <!-- Nama dan email pengguna yang ditampilkan di sidebar -->
                <div class="nama-pengguna"><?= htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="email-pengguna"><?= htmlspecialchars($user->email_address, ENT_QUOTES, 'UTF-8'); ?></div>
            </a>
            <!-- Menu navigasi utama di sidebar -->
            <div class="menu-bar">
                <!-- Link aktif ke Beranda -->
                <a class="item-side-bar-active link" href="sidebarBeranda">
                    <img oncontextmenu="return false;" class="icon-sidebar-active" alt="Icon Beranda" src="assets/img/icon_beranda.svg">
                    <div class="text-sidebar-active">Beranda</div>
                </a>
                <!-- Link default ke Guru Model -->
                <a class="item-side-bar-default link" href="sidebarGuruModel">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Icon Guru Model" src="assets/img/icon_guru_model.svg">
                    <div class="text-sidebar-default">Guru Model</div>
                </a>
                <!-- Link default ke Observer -->
                <a class="item-side-bar-default link" href="sidebarObserver">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Icon Observer" src="assets/img/icon_observer.svg">
                    <div class="text-sidebar-default">Observer</div>
                </a>
                <!-- Link default ke Bantuan -->
                <a class="item-side-bar-default link" href="sidebarBantuan">
                    <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Icon Bantuan" src="assets/img/icon_bantuan.svg">
                    <div class="text-sidebar-default">Bantuan</div>
                </a>
            </div>
            <!-- Link keluar dari aplikasi -->
            <a class="item-side-bar-exit link" href="sidebarLogout">
                <img oncontextmenu="return false;" class="icon-sidebar-default" alt="Icon Keluar" src="assets/img/icon_keluar.svg">
                <div class="text-sidebar-default">Keluar</div>
            </a>
        </div>
        <!-- Akhir bagian sidebar -->

        <!-- Bagian dashboard -->
        <div class="dashboard">Dashboard</div>
        <!-- Kontainer untuk menampilkan tanggal dan waktu -->
        <div class="date-container">
            <p id="dateDisplay" class="date"></p>
            <p id="timeDisplay" class="date"></p>
        </div>
        <!-- Grup frame untuk menampilkan statistik Lesson Study, Guru Model, dan Observer -->
        <div class="frame-group">
            <!-- Lesson Study (Kombinasi Guru Model dan Observer) -->
            <div class="group-parent2">
                <div class="rectangle-group">
                    <div class="background-icon-lesson-study-dashboard"></div>
                    <img oncontextmenu="return false;" class="icon-lesson-study-dashboard" alt="Icon Lesson Study" src="<?= base_url('assets/img/icon_lesson_study_dashboard.svg') ?>">
                </div>
                <div class="parent">
                    <!-- Menampilkan jumlah Lesson Study -->
                    <div class="div1"><?= htmlspecialchars($num_lesson_study, ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="lesson-study">Lesson Study</div>
                </div>
            </div>

            <!-- Guru Model -->
            <div class="group-parent2">
                <div class="rectangle-group">
                    <div class="background-icon-guru-model-dashboard"></div>
                    <img oncontextmenu="return false;" class="icon-guru-model-dashboard" alt="Icon Guru Model" src="<?= base_url('assets/img/icon_guru_model_dashboard.svg') ?>">
                </div>
                <div class="group">
                    <!-- Menampilkan jumlah kelas yang dibuat -->
                    <div class="div1"><?= htmlspecialchars($num_classes_created, ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="lesson-study">Guru Model</div>
                </div>
            </div>

            <!-- Observer -->
            <div class="group-parent2">
                <div class="rectangle-group">
                    <div class="background-icon-observer-dashboard"></div>
                    <img oncontextmenu="return false;" class="icon-observer-dashboard" alt="Icon Observer" src="<?= base_url('assets/img/icon_observer_dashboard.svg') ?>">
                </div>
                <div class="container">
                    <!-- Menampilkan jumlah kelas yang diamati -->
                    <div class="div1"><?= htmlspecialchars($num_classes_observed, ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="lesson-study">Observer</div>
                </div>
            </div>
        </div>
        <!-- Akhir bagian dashboard -->

        <!-- Bagian notifikasi -->
        <div class="frame-parent">
            <!-- Toggle untuk menampilkan notifikasi -->
            <div class="notifications-active-parent">
                <img oncontextmenu="return false;" class="notifications-active-icon" alt="Icon Notifikasi" src="assets/img/icon_notification.svg">
                <div class="notifikasi">Notifikasi</div>
                <div class="rectangle-parent">
                    <div class="group-child">
                    </div>
                    <!-- Menampilkan jumlah notifikasi jika lebih dari 0 -->
                    <?php if ($total_notifications > 0): ?>
                        <div class="div"><?php echo htmlspecialchars($total_notifications, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Menampilkan 4 notifikasi terbaru -->
            <?php foreach ($notifications_latest as $notification): ?>
                <div class="group-parent" data-id="<?php echo htmlspecialchars($notification->notification_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <!-- Menampilkan foto profil pengirim notifikasi -->
                    <img oncontextmenu="return false;" class="frame-child" alt="Foto Pengirim" src="<?php echo !empty($notification->sender_photo) ? base_url($notification->sender_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>">
                    <div class="notification-box-parent unselectable-text">
                        <div class="notification-box-container">
                            <span class="notification-box-container1">
                                <div class="notification-container">
                                    <!-- Menampilkan nama pengirim notifikasi -->
                                    <span class="notification-from">
                                        <?php echo isset($notification->sender_name) ? htmlspecialchars($notification->sender_name, ENT_QUOTES, 'UTF-8') : ''; ?>
                                    </span>
                                </div>
                                <!-- Menampilkan tipe notifikasi dengan pesan sesuai jenisnya -->
                                <span class="telah-mengundang-anda-menjadi">
                                    <?php if ($notification->notification_type == 'Observer Ditambahkan'): ?>
                                        <span>telah </span>
                                        <b class="observer">mengundang Anda </b>
                                        <span>untuk menjadi Observer.</span>
                                    <?php elseif ($notification->notification_type == 'Formulir Diisi'): ?>
                                        <span>telah melengkapi </span>
                                        <b class="observer">formulir penilaian </b>
                                        <span>untuk Lesson Study Anda.</span>
                                    <?php elseif ($notification->notification_type == 'Observer Bergabung'): ?>
                                        <span>telah bergabung sebagai Observer menggunakan </span>
                                        <b class="observer">kode kelas </b>
                                        <span>Anda.</span>
                                    <?php elseif ($notification->notification_type == 'Observer Dihapus'): ?>
                                        <span>telah </span>
                                        <b class="observer">menghapus Anda </b>
                                        <span>dari daftar anggota Observer Lesson Study.</span>
                                    <?php elseif ($notification->notification_type == 'Nomor Siswa Berubah'): ?>
                                        <span>telah memperbarui </span>
                                        <b class="observer">nomor siswa </b>
                                        <span>yang Anda amati selama Lesson Study.</span>
                                    <?php elseif ($notification->notification_type == 'Berkas Diperbarui'): ?>
                                        <span>telah </span>
                                        <b class="observer">memperbarui berkas </b>
                                        <span>untuk kegiatan Lesson Study Anda.</span>
                                    <?php elseif ($notification->notification_type == 'Detail Observasi Diperbarui'): ?>
                                        <span>telah memperbarui </span>
                                        <b class="observer">informasi detail </b>
                                        <span>pelaksanaan Lesson Study.</span>
                                    <?php endif; ?>
                                </span>
                            </span>
                        </div>
                        <!-- Menampilkan waktu notifikasi diterima -->
                        <div class="menit-yang-lalu">
                            <?php echo formatTime($notification->updated_at); // Menggunakan updated_at 
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if ($total_notifications < 1): ?>
                <!-- Jika tidak ada notifikasi, tampilkan pesan kosong -->
                <div class="empty_notification">
                    Belum ada notifikasi untuk Anda.
                </div>
            <?php endif; ?>

            <!-- Tombol untuk melihat lebih banyak notifikasi -->
            <?php if ($total_notifications > 4): ?>
                <div id="lihatSelengkapnya">
                    <div class="text-button unselectable-text">
                        <div class="isi-status">Lihat Selengkapnya</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <!-- Akhir bagian notifikasi -->

        <!-- Bagian jadwal terdekat -->
        <div class="jadwal-terdekat">Jadwal Terdekat</div>
        <?php if ($nearest_class): ?>
            <?php
            // Menentukan URL berdasarkan tipe kelas dengan menggunakan ID kelas yang terenkripsi
            if (isset($nearest_class->class_type) && $nearest_class->class_type == 'guru_model') {
                $detail_url = site_url('pageKelasGuruModel/' . htmlspecialchars($nearest_class->encrypted_class_id, ENT_QUOTES, 'UTF-8'));
            } else {
                $detail_url = site_url('pageKelasObserver1/' . htmlspecialchars($nearest_class->encrypted_class_id, ENT_QUOTES, 'UTF-8'));
            }
            ?>
            <!-- Link ke jadwal terdekat -->
            <a class="upcoming-schedule" href="<?php echo $detail_url; ?>">
                <div class="upcoming-schedule-child">
                </div>
                <div class="removebg-preview-1-parent">
                    <img oncontextmenu="return false;" class="removebg-preview-1-icon" alt="Icon Kalender" src="assets/img/graphic_calendar.svg">
                    <div class="ellipse-div"></div>
                </div>
                <div class="kelas-jadwal-terdekat-parent">
                    <div class="calendar-month-parent">
                        <img oncontextmenu="return false;" class="calendar-month-icon" alt="Icon Kalender" src="assets/img/icon_calendar.svg">
                        <!-- Menampilkan tanggal jadwal terdekat dalam format Indonesia -->
                        <div class="tanggal-jadwal-terdekat"><?php echo htmlspecialchars(indonesian_date($nearest_class->date), ENT_QUOTES, 'UTF-8'); ?></div>
                        <img oncontextmenu="return false;" class="alarm-icon" alt="Icon Alarm" src="assets/img/icon_alarm.svg">
                        <!-- Menampilkan jam mulai dan akhir kelas -->
                        <div class="jam-jadwal-terdekat"><?php echo htmlspecialchars(date('H:i', strtotime($nearest_class->start_time)) . ' - ' . date('H:i', strtotime($nearest_class->end_time)), ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <!-- Menampilkan nama kelas dan sekolah -->
                    <div class="kelas-jadwal-terdekat"><?php echo htmlspecialchars($nearest_class->class_name, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="sekolah-jadwal-terdekat"><?php echo htmlspecialchars($nearest_class->school_name, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="guru-model-parent">
                        <div class="guru-model2">Guru Model:</div>
                        <div class="observer5">Observer:</div>
                        <!-- Menampilkan foto Guru Model -->
                        <img oncontextmenu="return false;" class="group-child1" alt="Foto Guru Model" src="<?php echo base_url(!empty($nearest_class->guru_model_photo) ? $nearest_class->guru_model_photo : 'assets/default/default_profile_picture.jpg'); ?>">
                        <div class="vector-parent">
                            <?php if (!empty($nearest_class->latest_observers)): ?>
                                <?php foreach ($nearest_class->latest_observers as $observer): ?>
                                    <!-- Menampilkan foto Observer terbaru -->
                                    <img oncontextmenu="return false;" class="frame-child2" alt="Foto Observer" src="<?php echo base_url(!empty($observer->src_profile_photo) ? $observer->src_profile_photo : 'assets/default/default_profile_picture.jpg'); ?>">
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span>--</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <img oncontextmenu="return false;" class="vector-icon" alt="Icon Next" src="assets/img/icon_jadwal_terdekat_next.svg">
                </div>
            </a>
        <?php else: ?>
            <!-- Jika tidak ada jadwal terdekat, tampilkan placeholder dengan "--" -->
            <a class="upcoming-schedule" href="#">
                <div class="upcoming-schedule-child">
                    <!-- Placeholder atau pesan kosong -->
                </div>
                <div class="removebg-preview-1-parent">
                    <img oncontextmenu="return false;" class="removebg-preview-1-icon" alt="Icon Kalender" src="assets/img/graphic_calendar.svg">
                    <div class="ellipse-div"></div>
                </div>
                <div class="kelas-jadwal-terdekat-parent">
                    <div class="calendar-month-parent">
                        <img oncontextmenu="return false;" class="calendar-month-icon" alt="Icon Kalender" src="assets/img/icon_calendar.svg">
                        <div class="tanggal-jadwal-terdekat"></div>
                        <img oncontextmenu="return false;" class="alarm-icon" alt="Icon Alarm" src="assets/img/icon_alarm.svg">
                        <div class="jam-jadwal-terdekat"></div>
                    </div>
                    <div class="kelas-jadwal-terdekat">Yey! Tidak ada jadwal observasi terdekat.</div>
                    <div class="sekolah-jadwal-terdekat">Nikmati waktu luang Anda!</div>
                    <div class="guru-model-parent">
                        <div class="guru-model2">Guru Model:</div>
                        <div class="observer5">Observer:</div>
                        <span></span>
                        <div class="vector-parent">
                            <span></span>
                        </div>
                    </div>
                    <img oncontextmenu="return false;" class="vector-icon" alt="Icon Next" src="assets/img/icon_jadwal_terdekat_next.svg">
                </div>
            </a>
        <?php endif; ?>
        <!-- Akhir bagian jadwal terdekat -->

        <!-- Bagian aktivitas saya -->
        <div class="frame-container">
            <div class="aktivitas-saya-parent">
                <div class="aktivitas-saya">Aktivitas Saya</div>
                <div class="search-parent">
                    <img oncontextmenu="return false;" class="notifications-active-icon1" alt="Icon Search" src="assets/img/icon_search.svg">
                    <div class="cari-disini">
                        <div class="search-parent">
                            <!-- Input pencarian untuk aktivitas saya -->
                            <input type="text" name="search" class="search-input" placeholder="Cari Disini....." />
                        </div>
                    </div>
                </div>
            </div>
            <div class="frame-parent1">
                <!-- Header Kolom untuk tabel kelas -->
                <div class="nama-kelas-parent">
                    <div class="judul-nama-kelas unselectable-text" data-column="data-nama-kelas">Nama Kelas</div>
                    <div class="judul-mata-pelajaran unselectable-text" data-column="data-mata-pelajaran">Mata Pelajaran</div>
                    <div class="judul-guru-model unselectable-text" data-column="data-guru-model">Guru Model</div>
                    <div class="judul-observer unselectable-text">Observer</div>
                    <div class="judul-tanggal unselectable-text" data-column="tanggal-aktivitas-saya">Tanggal</div>
                    <div class="judul-status unselectable-text" data-column="isi-status">Status</div>
                </div>

                <!-- Kontainer Tabel Kelas -->
                <div class="frame-parent2" id="table-container">
                    <?php if (!empty($classes)) : ?>
                        <?php foreach ($classes as $class) : ?>
                            <?php
                            // Menentukan URL berdasarkan jenis kelas menggunakan ID kelas yang terenkripsi
                            if (isset($class->class_type) && $class->class_type === 'observer') {
                                $url = site_url('pageKelasObserver1/' . htmlspecialchars($class->encrypted_class_id, ENT_QUOTES, 'UTF-8'));
                            } else { // 'guru_model'
                                $url = site_url('pageKelasGuruModel/' . htmlspecialchars($class->encrypted_class_id, ENT_QUOTES, 'UTF-8'));
                            }
                            ?>
                            <!-- Link ke detail kelas -->
                            <a class="item-tabel link" href="<?php echo $url; ?>">
                                <!-- Nama Kelas -->
                                <div class="data-nama-kelas"><?php echo htmlspecialchars($class->class_name, ENT_QUOTES, 'UTF-8'); ?></div>

                                <!-- Mata Pelajaran -->
                                <div class="data-mata-pelajaran"><?php echo htmlspecialchars($class->subject, ENT_QUOTES, 'UTF-8'); ?></div>

                                <!-- Guru Model -->
                                <div class="data-guru-model">
                                    <?php echo htmlspecialchars($class->creator_user_id ? getUserName($class->creator_user_id) : 'Tidak Ada Guru Model', ENT_QUOTES, 'UTF-8'); ?>
                                </div>

                                <!-- Observer Terbaru -->
                                <div class="profile-groupvariant9">
                                    <?php
                                    // Menampilkan 4 observer terbaru atau pesan jika tidak ada
                                    if (!empty($class->latest_observers)) {
                                        foreach ($class->latest_observers as $observer) {
                                            echo '<img oncontextmenu="return false;" class="first-observer-frame" alt="' . htmlspecialchars($observer->full_name, ENT_QUOTES, 'UTF-8') . '" src="' . (!empty($observer->src_profile_photo) ? base_url($observer->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg')) . '">';
                                        }
                                    } else {
                                        echo '<div class="no-observers">Tidak Ada Observer</div>';
                                    }
                                    ?>
                                </div>

                                <!-- Tanggal Aktivitas -->
                                <div class="tanggal-aktivitas-saya"><?php echo htmlspecialchars(indonesian_date($class->date), ENT_QUOTES, 'UTF-8'); ?></div>

                                <!-- Status -->
                                <?php
                                // Menentukan kelas status berdasarkan status
                                $status = isset($class->status) ? $class->status : 'Tidak Diketahui';
                                $status_class = '';
                                if ($status == 'Akan Datang') {
                                    $status_class = 'status-label';
                                } elseif ($status == 'Belum Dilengkapi') {
                                    $status_class = 'status-label2';
                                } elseif ($status == 'Selesai') {
                                    $status_class = 'status-label3';
                                }
                                ?>
                                <div class="<?php echo htmlspecialchars($status_class, ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="isi-status"><?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Jika tidak ada aktivitas, tampilkan pesan kosong -->
                        <div class="empty_activity">Anda belum memiliki aktivitas terkini.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Akhir bagian aktivitas saya -->

        <?php
        // Fungsi untuk mendapatkan nama pengguna berdasarkan ID
        function getUserName($user_id)
        {
            $CI = &get_instance();
            $CI->load->model('User');
            $user = $CI->User->getUserById($user_id);
            return $user ? htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8') : 'Tidak Diketahui';
        }
        ?>
    </div>
    <!-- Pop up notifikasi -->
    <div class="parent1" id="popupContainer">
        <div id="popupNotification" class="detail-notifikasi popup-hidden">
            <div class="notifications-active-parent">
                <img oncontextmenu="return false;" class="notifications-active-icon" alt="Icon Notifikasi" src="assets/img/icon_notification.svg">
                <div class="notifikasi">Notifikasi</div>
                <div class="rectangle-parent">
                    <div class="group-child"></div>
                    <!-- Menampilkan jumlah notifikasi jika lebih dari 0 -->
                    <?php if ($total_notifications > 0): ?>
                        <div class="div"><?php echo htmlspecialchars($total_notifications, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="frame-parent3">
                <!-- Menampilkan semua notifikasi -->
                <?php foreach ($notifications_all as $notification): ?>
                    <div class="group-parent" data-id="<?php echo htmlspecialchars($notification->notification_id, ENT_QUOTES, 'UTF-8'); ?>">
                        <!-- Menampilkan foto profil pengirim notifikasi -->
                        <img oncontextmenu="return false;" class="frame-child" alt="Foto Pengirim" src="<?php echo !empty($notification->sender_photo) ? base_url($notification->sender_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>">
                        <div class="notification-box-parent1 unselectable-text">
                            <div class="notification-box-container2">
                                <span class="notification-box-container1">
                                    <div class="notification-container1">
                                        <!-- Menampilkan nama pengirim notifikasi -->
                                        <span class="notification-from1">
                                            <?php echo isset($notification->sender_name) ? htmlspecialchars($notification->sender_name, ENT_QUOTES, 'UTF-8') : 'Pengirim Tidak Diketahui'; ?>
                                        </span>
                                    </div>
                                    <!-- Menampilkan tipe notifikasi dengan pesan sesuai jenisnya -->
                                    <span class="telah-mengundang-anda-menjadi">
                                        <?php if ($notification->notification_type == 'Observer Ditambahkan'): ?>
                                            <span>telah </span>
                                            <b class="observer">mengundang Anda </b>
                                            <span>untuk menjadi Observer.</span>
                                        <?php elseif ($notification->notification_type == 'Formulir Diisi'): ?>
                                            <span>telah melengkapi </span>
                                            <b class="observer">formulir penilaian </b>
                                            <span>untuk Lesson Study Anda.</span>
                                        <?php elseif ($notification->notification_type == 'Observer Bergabung'): ?>
                                            <span>telah bergabung sebagai Observer menggunakan </span>
                                            <b class="observer">kode kelas </b>
                                            <span>Anda.</span>
                                        <?php elseif ($notification->notification_type == 'Observer Dihapus'): ?>
                                            <span>telah </span>
                                            <b class="observer">menghapus Anda </b>
                                            <span>dari daftar anggota Observer Lesson Study.</span>
                                        <?php elseif ($notification->notification_type == 'Nomor Siswa Berubah'): ?>
                                            <span>telah memperbarui </span>
                                            <b class="observer">nomor siswa </b>
                                            <span>yang Anda amati selama Lesson Study.</span>
                                        <?php elseif ($notification->notification_type == 'Berkas Diperbarui'): ?>
                                            <span>telah </span>
                                            <b class="observer">memperbarui berkas </b>
                                            <span>untuk kegiatan Lesson Study Anda.</span>
                                        <?php elseif ($notification->notification_type == 'Detail Observasi Diperbarui'): ?>
                                            <span>telah memperbarui </span>
                                            <b class="observer">informasi detail </b>
                                            <span>pelaksanaan Lesson Study.</span>
                                        <?php endif; ?>
                                    </span>
                                </span>
                                <!-- Menampilkan waktu notifikasi diterima -->
                                <div class="menit-yang-lalu1">
                                    <?php echo formatTime($notification->updated_at); // Menggunakan updated_at 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Tombol untuk menutup popup notifikasi -->
            <div id="tutupNotifikasi" class="text-button-wrapper">
                <div class="text-button1">
                    <div class="isi-status link-cursor-hovering">Tutup</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir pop up notifikasi -->
    <!-- Overlay untuk efek blur saat popup ditampilkan -->
    <div id="overlay"></div>
</body>

<script>
    // Menunggu hingga halaman selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        // Mendapatkan elemen flashdata
        var flashdata = document.getElementById('flashdata');
        var success = flashdata.getAttribute('data-success');
        var error = flashdata.getAttribute('data-error');
        var classNotStarted = flashdata.getAttribute('data-class-not-started');
        var warning = flashdata.getAttribute('data-warning');
        var notice = flashdata.getAttribute('data-notice');
        var deprecated = flashdata.getAttribute('data-deprecated');
        var errorLong = flashdata.getAttribute('data-error-long');
        var successLogin = flashdata.getAttribute('data-success-login');
        var alreadyLoggedIn = flashdata.getAttribute('data-already-logged-in'); // Menambahkan ini

        // Array untuk menyimpan semua pesan notifikasi
        var messages = [];

        // Menambahkan pesan already_logged_in jika ada
        if (alreadyLoggedIn) {
            messages.push({
                icon: 'error',
                title: 'Akses Ditolak',
                text: alreadyLoggedIn,
                confirmButtonColor: '#2563EB',
                timer: 3000, // 3 detik
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        // Menambahkan pesan success_login jika ada
        if (successLogin) {
            messages.push({
                icon: 'success',
                title: 'Berhasil',
                text: successLogin,
                confirmButtonColor: '#2563EB',
                timer: 2000, // 2 detik
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        // Menambahkan pesan sukses ke array jika ada
        if (success) {
            messages.push({
                icon: 'success',
                title: 'Berhasil',
                text: success,
                confirmButtonColor: '#2563EB'
            });
        }

        // Menambahkan pesan error ke array jika ada
        if (error) {
            messages.push({
                icon: 'error',
                title: 'Error',
                text: error,
                confirmButtonColor: '#2563EB'
            });
        }

        // Menambahkan pesan class_not_started ke array jika ada
        if (classNotStarted) {
            messages.push({
                icon: 'warning',
                title: 'Akses Ditolak',
                text: classNotStarted,
                confirmButtonColor: '#2563EB'
            });
        }

        // Menambahkan pesan warning ke array jika ada
        if (warning) {
            messages.push({
                icon: 'warning',
                title: 'Peringatan',
                text: warning,
                confirmButtonColor: '#F59E0B'
            });
        }

        // Menambahkan pesan notice ke array jika ada
        if (notice) {
            messages.push({
                icon: 'info',
                title: 'Pemberitahuan',
                text: notice,
                confirmButtonColor: '#2563EB'
            });
        }

        // Menambahkan pesan deprecated ke array jika ada
        if (deprecated) {
            messages.push({
                icon: 'warning',
                title: 'Deprecated',
                text: deprecated,
                confirmButtonColor: '#F59E0B'
            });
        }

        // Menambahkan pesan error panjang ke array jika ada
        if (errorLong) {
            messages.push({
                icon: 'error',
                title: 'Error',
                html: '<pre style="text-align: left;">' + errorLong + '</pre>',
                customClass: {
                    popup: 'swal-wide'
                },
                confirmButtonColor: '#2563EB'
            });
        }

        /**
         * Fungsi untuk menampilkan pesan notifikasi secara bergantian
         *
         * @param {Array} msgs Array yang berisi objek pesan notifikasi
         */
        function showMessagesSequentially(msgs) {
            if (msgs.length === 0) return;

            // Clone array untuk menghindari modifikasi array asli
            var queue = msgs.slice();

            /**
             * Fungsi rekursif untuk menampilkan pesan satu per satu
             */
            function showNext() {
                if (queue.length === 0) return;

                var currentMsg = queue.shift();
                Swal.fire({
                    icon: currentMsg.icon,
                    title: currentMsg.title,
                    text: currentMsg.text,
                    html: currentMsg.html || undefined,
                    customClass: currentMsg.customClass || undefined,
                    confirmButtonText: 'OK',
                    confirmButtonColor: currentMsg.confirmButtonColor,
                    timer: currentMsg.timer || undefined,
                    timerProgressBar: currentMsg.timer ? currentMsg.timerProgressBar : undefined,
                    showConfirmButton: currentMsg.showConfirmButton !== undefined ? currentMsg.showConfirmButton : true
                }).then(function() {
                    showNext();
                });
            }

            showNext();
        }

        // Memanggil fungsi untuk menampilkan pesan notifikasi
        showMessagesSequentially(messages);
    });

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

        // Mengupdate elemen HTML dengan id 'dateDisplay' dan 'timeDisplay'
        document.getElementById('dateDisplay').innerText = dateString;
        document.getElementById('timeDisplay').innerText = timeString;
    }

    // Memanggil fungsi updateDateTime setiap detik untuk real-time update
    setInterval(updateDateTime, 1000);

    // Memastikan waktu saat ini ditampilkan saat memuat halaman
    updateDateTime();

    // Fungsi untuk menangani pop-up notifikasi
    document.addEventListener("DOMContentLoaded", function() {
        var popup = document.getElementById('popupNotification');
        var overlay = document.getElementById('overlay');

        // Pastikan popup dan overlay tersembunyi saat halaman dimuat
        popup.classList.add('popup-hidden');
        overlay.classList.add('overlay-hidden');

        // Tambahkan event listener untuk menampilkan popup saat tombol 'Lihat Selengkapnya' diklik
        document.getElementById('lihatSelengkapnya').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default
            popup.classList.remove('popup-hidden');
            popup.classList.add('popup-visible');
            overlay.classList.remove('overlay-hidden');
            overlay.classList.add('overlay-visible');
            document.body.classList.add('blur'); // Tambahkan efek blur pada background
        });

        // Tambahkan event listener untuk menyembunyikan popup saat tombol 'Tutup' diklik
        document.getElementById('tutupNotifikasi').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default
            popup.classList.remove('popup-visible');
            popup.classList.add('popup-hidden');
            overlay.classList.remove('overlay-visible');
            overlay.classList.add('overlay-hidden');
            document.body.classList.remove('blur'); // Hapus efek blur dari background
        });

        // Tambahkan event listener untuk menyembunyikan popup saat mengklik di luar popup
        overlay.addEventListener('click', function(event) {
            popup.classList.remove('popup-visible');
            popup.classList.add('popup-hidden');
            overlay.classList.remove('overlay-visible');
            overlay.classList.add('overlay-hidden');
            document.body.classList.remove('blur'); // Hapus efek blur dari background
        });

        // Mencegah klik di dalam popup untuk menutupnya
        popup.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });

    // Fungsi untuk melakukan pencarian pada tabel kelas
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('.search-input');
        const tabelLinks = document.querySelectorAll('.item-tabel');

        /**
         * Event listener untuk input pencarian
         */
        searchInput.addEventListener('input', function() {
            const searchQuery = searchInput.value.toLowerCase();

            tabelLinks.forEach(link => {
                // Mengambil teks dari setiap kolom yang relevan
                const namaKelas = link.querySelector('.data-nama-kelas').textContent.toLowerCase();
                const mataPelajaran = link.querySelector('.data-mata-pelajaran').textContent.toLowerCase();
                const guruModel = link.querySelector('.data-guru-model').textContent.toLowerCase();
                const tanggal = link.querySelector('.tanggal-aktivitas-saya').textContent.toLowerCase();
                const status = link.querySelector('.isi-status').textContent.toLowerCase();

                // Menampilkan link jika query pencarian cocok dengan salah satu bidang
                if (namaKelas.includes(searchQuery) || mataPelajaran.includes(searchQuery) ||
                    guruModel.includes(searchQuery) || tanggal.includes(searchQuery) ||
                    status.includes(searchQuery)) {
                    link.style.display = '';
                } else {
                    link.style.display = 'none';
                }
            });
        });
    });

    // Fungsi untuk menangani pengurutan tabel kelas
    document.addEventListener("DOMContentLoaded", function() {
        const tableContainer = document.getElementById("table-container");
        const headers = document.querySelectorAll(".nama-kelas-parent > div[data-column]");
        let currentSort = {
            column: '',
            order: 'asc',
            clicks: {}
        };
        const originalOrder = Array.from(tableContainer.getElementsByClassName("item-tabel"));

        // Menambahkan event listener pada setiap header kolom untuk pengurutan
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const column = header.getAttribute('data-column');
                handleSort(column, header);
            });
        });

        /**
         * Fungsi untuk menangani pengurutan berdasarkan kolom
         *
         * @param {string} column Nama Kolom yang akan diurutkan
         * @param {Element} header Elemen Header yang diklik
         */
        function handleSort(column, header) {
            const items = Array.from(tableContainer.getElementsByClassName("item-tabel"));

            // Menghitung jumlah klik pada kolom tertentu
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

                // Mengurutkan berdasarkan prioritas status
                items.sort((a, b) => {
                    const aStatus = a.getElementsByClassName('isi-status')[0].innerText.trim();
                    const bStatus = b.getElementsByClassName('isi-status')[0].innerText.trim();

                    return priority.indexOf(aStatus) - priority.indexOf(bStatus);
                });
            } else {
                // Mengurutkan berdasarkan teks dalam kolom
                items.sort((a, b) => {
                    const aText = a.querySelector(`.${column}`).textContent.trim().toLowerCase();
                    const bText = b.querySelector(`.${column}`).textContent.trim().toLowerCase();

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

            // Menghapus semua elemen dalam tabel
            while (tableContainer.firstChild) {
                tableContainer.removeChild(tableContainer.firstChild);
            }

            // Menambahkan elemen yang sudah diurutkan ke dalam tabel
            items.forEach(item => tableContainer.appendChild(item));
            updateHeaders(header);
        }

        /**
         * Fungsi untuk mengubah format tanggal dari string menjadi objek Date
         *
         * @param {string} dateString String Tanggal dalam format Indonesia
         * @return {Date} Objek Date yang sesuai
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
            return new Date(year, monthMap[month], day);
        }

        /**
         * Fungsi untuk memperbarui tampilan header setelah pengurutan
         *
         * @param {Element} activeHeader Elemen Header yang Aktif
         */
        function updateHeaders(activeHeader) {
            // Menghapus semua panah pengurutan dari header
            headers.forEach(header => header.innerText = header.innerText.replace(/[\u2191\u2193]/g, ''));
            // Menambahkan panah sesuai dengan urutan pengurutan saat ini
            if (currentSort.column === 'isi-status' && currentSort.clicks['isi-status'] > 0) {
                activeHeader.innerText += ' \u2193';
            } else if (currentSort.order !== 'default') {
                activeHeader.innerText += currentSort.order === 'asc' ? ' \u2191' : ' \u2193';
            }
        }

        /**
         * Fungsi untuk mereset tabel ke urutan awal
         */
        function resetTable() {
            // Menghapus semua elemen dalam tabel
            while (tableContainer.firstChild) {
                tableContainer.removeChild(tableContainer.firstChild);
            }

            // Menambahkan kembali elemen dalam urutan asli
            originalOrder.forEach(item => tableContainer.appendChild(item));
            // Menghapus semua panah pengurutan dari header
            headers.forEach(header => header.innerText = header.innerText.replace(/[\u2191\u2193]/g, ''));
        }
    });

    // Fungsi untuk menangani notifikasi
    document.addEventListener("DOMContentLoaded", function() {
        const mainNotifications = document.querySelectorAll(".frame-parent .group-parent");
        const popupNotifications = document.querySelectorAll("#popupNotification .group-parent");
        const counterElement = document.querySelector(".frame-parent .div");
        const popupCounterElement = document.querySelector("#popupNotification .div");
        const notificationCounterContainer = document.querySelector(".frame-parent .rectangle-parent");
        const popupCounterContainer = document.querySelector("#popupNotification .rectangle-parent");
        const notificationIcons = document.querySelectorAll(".notifications-active-icon");

        /**
         * Fungsi untuk memperbarui jumlah notifikasi yang belum dibaca
         */
        function updateCounter() {
            const uniqueUnreadIds = new Set();

            // Menambahkan ID notifikasi yang belum dibaca dari notifikasi utama
            mainNotifications.forEach(notification => {
                const id = notification.getAttribute("data-id");
                if (!notification.classList.contains("read")) {
                    uniqueUnreadIds.add(id);
                }
            });

            // Menambahkan ID notifikasi yang belum dibaca dari popup notifikasi
            popupNotifications.forEach(notification => {
                const id = notification.getAttribute("data-id");
                if (!notification.classList.contains("read")) {
                    uniqueUnreadIds.add(id);
                }
            });

            const unreadCount = uniqueUnreadIds.size;

            // Mengatur jumlah notifikasi untuk tampilan utama dan popup
            if (counterElement) {
                counterElement.textContent = unreadCount;
            }
            if (popupCounterElement) {
                popupCounterElement.textContent = unreadCount;
            }

            if (unreadCount === 0) {
                // Menyembunyikan counter notifikasi jika tidak ada notifikasi
                if (notificationCounterContainer) {
                    notificationCounterContainer.style.transition = "opacity 0.5s ease";
                    notificationCounterContainer.style.opacity = "0";
                    setTimeout(() => {
                        notificationCounterContainer.style.display = "none";
                        popupCounterContainer.style.display = "none";
                    }, 500);
                }
                setTimeout(() => {
                    notificationIcons.forEach(icon => icon.classList.remove("shake"));
                }, 2000);
            } else {
                // Menampilkan counter notifikasi jika ada notifikasi
                if (notificationCounterContainer) {
                    notificationCounterContainer.style.display = "block";
                    notificationCounterContainer.style.opacity = "1";
                }
                if (popupCounterContainer) {
                    popupCounterContainer.style.display = "block";
                    popupCounterContainer.style.opacity = "1";
                }

                if (unreadCount > 0) {
                    notificationIcons.forEach(icon => icon.classList.add("shake"));
                } else {
                    notificationIcons.forEach(icon => icon.classList.remove("shake"));
                }
            }
        }

        /**
         * Fungsi untuk menandai notifikasi sebagai sudah dibaca
         *
         * @param {Element} notification Elemen Notifikasi yang akan ditandai
         */
        function markAsRead(notification) {
            const id = notification.getAttribute("data-id");
            notification.classList.add("read");
            notification.style.transition = "background-color 0.2s ease-in-out";
            notification.style.backgroundColor = "transparent";
            localStorage.setItem(`notification-${id}`, "read");
            syncNotifications(id);
        }

        /**
         * Fungsi untuk menyinkronkan status notifikasi
         *
         * @param {int} id ID Notifikasi yang akan disinkronkan
         */
        function syncNotifications(id) {
            const mainNotification = document.querySelector(`.frame-parent .group-parent[data-id="${id}"]`);
            const popupNotification = document.querySelector(`#popupNotification .group-parent[data-id="${id}"]`);

            if (mainNotification) {
                mainNotification.classList.add("read");
                mainNotification.style.backgroundColor = "transparent";
            }

            if (popupNotification) {
                popupNotification.classList.add("read");
                popupNotification.style.backgroundColor = "transparent";
            }

            updateCounter();
        }

        /**
         * Fungsi untuk menginisialisasi notifikasi
         */
        function initializeNotifications() {
            const allNotifications = [...mainNotifications, ...popupNotifications];

            allNotifications.forEach(notification => {
                const id = notification.getAttribute("data-id");

                // Cek status baca di localStorage
                if (localStorage.getItem(`notification-${id}`) === "read") {
                    notification.classList.add("read");
                    notification.style.backgroundColor = "transparent";
                }

                // Menandai notifikasi sebagai sudah dibaca saat mouse masuk
                notification.addEventListener("mouseenter", function() {
                    markAsRead(notification);
                });
            });

            // Inisialisasi counter notifikasi
            updateCounter();
        }

        initializeNotifications();
    });

    // Fungsi untuk mengatur fokus pada input pencarian
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('.search-input');

        /**
         * Fungsi untuk menetapkan fokus pada input pencarian dengan delay
         */
        function setFocus() {
            setTimeout(() => {
                searchInput.focus();
            }, 300); // Tambahkan delay untuk memastikan modal sepenuhnya terlihat sebelum fokus
        }

        setFocus();
    });

    /**
     * Fungsi yang dijalankan saat window dimuat
     */
    window.onload = function() {
        // Daftar pasangan elemen target dan induknya
        const targetPairs = [{
                targetClass: "notification-from",
                parentClass: "notification-box-parent"
            },
            {
                targetClass: "notification-from1",
                parentClass: "notification-box-parent1"
            }
        ];

        // Daftar kelas atau atribut data yang akan dianimasikan secara langsung
        const directClassesToAnimate = [
            "data-nama-kelas",
            "data-mata-pelajaran",
            "data-guru-model",
            "tanggal-aktivitas-saya",
            "nama-pengguna",
            "email-pengguna",
            "kelas-jadwal-terdekat",
            "sekolah-jadwal-terdekat"
        ];

        // Gabungan semua elemen yang perlu dianimasikan
        const elementsToAnimate = [];

        /**
         * Fungsi untuk memeriksa apakah elemen benar-benar overflow
         *
         * @param {Element} element Elemen HTML yang akan diperiksa
         * @return {Object} Objek yang berisi informasi apakah overflow dan perbedaannya
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
         *
         * @param {Element} element Elemen HTML yang akan dianimasikan
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
                let animationId = null; // Ubah dari const ke let

                /**
                 * Fungsi animasi menggunakan requestAnimationFrame
                 *
                 * @param {DOMHighResTimeStamp} timestamp Waktu saat frame animasi
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
         * Menambahkan event listener untuk resize window agar animasi disesuaikan
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