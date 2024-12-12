<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Aset Global untuk Semua Halaman
|--------------------------------------------------------------------------
|
| Daftar file CSS dan JS yang akan disisipkan di semua halaman view.
|
*/

$config['global_css'] = array(
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
    'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;600;700&display=swap',
    'https://fonts.googleapis.com/css2?family=Oleo+Script:wght@400;700&display=swap',
    'assets/css/global.css'
);

$config['global_js'] = array(
    'assets/js/hideConsole.js',
    'assets/js/responsive_new.js',
    'assets/js/sweetalert2.all.js',
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
    // Tambahkan jika diperlukan CSS khusus untuk halaman login
);

$config['auth_js'] = array(
    'assets/js/axios.js',
    'assets/js/heartbeat_new.js'
);
