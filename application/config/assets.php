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
    // CSS dari CDN untuk semua halaman
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
    'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;600;700&display=swap',
    'https://fonts.googleapis.com/css2?family=Oleo+Script:wght@400;700&display=swap',

    // CSS Lokal untuk semua halaman
    'assets/css/global.css'
);

$config['global_js'] = array(
    // JS dari CDN untuk semua halaman
    // 'https://cdn.jsdelivr.net/npm/sweetalert2@11',

    // JS Lokal untuk semua halaman
    'assets/js/responsive_new.js',
    'assets/js/sweetalert2.all.js',
    'assets/js/detectIncognito.js',
    'assets/js/detectIncognitoAlert.js'
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
    // CSS dari CDN untuk halaman yang memerlukan login
    // Tambahkan jika diperlukan
    
    // CSS lokal
    // Tambahkan jika diperlukan
);

$config['auth_js'] = array(
    // JS dari CDN untuk halaman yang memerlukan login
    // 'https://code.jquery.com/jquery-3.6.0.min.js',
    
    // JS Lokal 
    'assets/js/axios.js',
    'assets/js/heartbeat.js'
);