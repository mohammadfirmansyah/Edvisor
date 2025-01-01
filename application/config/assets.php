<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Favicon
|--------------------------------------------------------------------------
|
| URL atau path relatif untuk favicon yang akan disisipkan di semua halaman.
|
*/
$config['favicon'] = 'assets/img/favicon.png';

/*
|--------------------------------------------------------------------------
| Aset Global Images untuk Semua Halaman
|--------------------------------------------------------------------------
|
| Daftar file gambar yang akan disimpan di IndexedDB dan digunakan di semua halaman.
|
*/
$config['global_images'] = array(
    'assets/gif/loading.gif',
    'assets/gif/loading_success.gif',
    'assets/gif/loading_failed.gif'
);

/*
|--------------------------------------------------------------------------
| Aset Global untuk Semua Halaman
|--------------------------------------------------------------------------
|
| Daftar file CSS dan JS yang akan disisipkan di semua halaman view.
|
*/
$config['global_css'] = array(
    'assets/css/sweetalert2.css',
    'assets/css/global.css'
);

$config['global_js'] = array(
    'assets/js/fonts.js', // Menggunakan font lokal
    'assets/js/sweetalert2.js',
    'assets/js/sweetalertFocus.js',
    'assets/js/loading.js',
    'assets/js/jquery.js',
    'assets/js/hideConsole.js',
    'assets/js/responsive.js',
    'assets/js/detectIncognito.js',
    // 'assets/js/detectIncognitoAlert.js'
);

/*
|--------------------------------------------------------------------------
| Aset Tambahan untuk Halaman yang Memerlukan Login
|--------------------------------------------------------------------------
|
| Daftar file CSS dan JS tambahan yang akan disisipkan hanya pada halaman
| yang memerlukan akses login.
|
*/
$config['auth_css'] = array(
    // Tambahkan jika diperlukan CSS khusus untuk halaman yang memerlukan akses login.
);

$config['auth_js'] = array(
    'assets/js/axios.js',
    'assets/js/heartbeat.js'
);

/*
|--------------------------------------------------------------------------
| Aset Khusus Per Halaman
|--------------------------------------------------------------------------
|
| Daftar file CSS dan JS yang akan disisipkan hanya pada halaman tertentu,
| berdasarkan nama metode (fungsi) pada controller.
|
| Format:
| 'method_name' => array(
|     'css' => array(...),
|     'js'  => array(...)
| )
|
*/
$config['page_assets'] = array(
    // Bantuan.php
    'sidebarBantuan' => array(
        'css' => array(
            'assets/css/bantuan.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // Beranda.php
    'sidebarBeranda' => array(
        'css' => array(
            'assets/css/beranda.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // buatKelas1.php
    'pageBuatKelas_detailKelas' => array(
        'css' => array(
            'assets/css/buatkelas1.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // buatKelas2.php
    'pageBuatKelas_unggahBerkas' => array(
        'css' => array(
            'assets/css/buatkelas2.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // buatKelas3.php
    'pageBuatKelas_detailObserver' => array(
        'css' => array(
            'assets/css/buatkelas3.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // editKelas1.php
    'pageEditKelas_detailKelas' => array(
        'css' => array(
            'assets/css/editkelas1.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // editKelas2.php
    'pageEditKelas_unggahBerkas' => array(
        'css' => array(
            'assets/css/editkelas2.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // editKelas3.php
    'pageEditKelas_detailObserver' => array(
        'css' => array(
            'assets/css/editkelas3.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // guruModel.php
    'sidebarGuruModel' => array(
        'css' => array(
            'assets/css/gurumodel.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // kelasGuruModel.php
    'pageKelasGuruModel' => array(
        'css' => array(
            'assets/css/kelasgurumodel.css'
        ),
        'js' => array(
            'assets/js/wavesurfer.js'
        )
    ),
    // kelasObserver1.php
    'pageKelasObserver1' => array(
        'css' => array(
            'assets/css/kelasobserver1.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // kelasObserver2.php
    'pageKelasObserver2' => array(
        'css' => array(
            'assets/css/kelasobserver2.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // kelasObserver3.php
    'pageKelasObserver3' => array(
        'css' => array(
            'assets/css/kelasobserver3.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // Login.php
    'pageLogin' => array(
        'css' => array(
            'assets/css/login.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // lupaKataSandi.php
    'pageLupaKataSandi' => array(
        'css' => array(
            'assets/css/lupakatasandi.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // Observer.php
    'sidebarObserver' => array(
        'css' => array(
            'assets/css/observer.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // previewForm.php (Dikecualikan menerima aset global dan auth)
    'previewForm' => array(
        'css' => array(
            'assets/css/previewform.css',
            'assets/css/sweetalert2.css',
            'assets/css/global.css',
        ),
        'js' => array(
            'assets/js/fonts.js',
            'assets/js/jszip.js',
            'assets/js/docx-preview.js',
            'assets/js/responsive.js',
            'assets/js/hideConsole.js',
            'assets/js/sweetalert2.js'
        )
    ),
    // Profil.php
    'sidebarProfile' => array(
        'css' => array(
            'assets/css/profil.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    ),
    // Signup.php
    'pageSignup' => array(
        'css' => array(
            'assets/css/signup.css'
        ),
        'js' => array(
            // Tambahkan jika memerlukan JavaScript khusus
        )
    )
);
