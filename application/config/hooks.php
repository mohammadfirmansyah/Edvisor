<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Konfigurasi Hooks
|--------------------------------------------------------------------------
|
| Mengatur hook yang akan digunakan untuk menyisipkan aset global dan aset
| tambahan berdasarkan status login pengguna.
|
*/

$hook['display_override'] = array(
    'class'    => 'GlobalAssetsInjector',      // Nama kelas hook
    'function' => 'injectAssets',              // Nama metode yang akan dipanggil
    'filename' => 'GlobalAssetsInjector.php',  // Nama file hook
    'filepath' => 'hooks',                     // Lokasi file hook relatif dari direktori hooks
    'params'   => array()                       // Parameter tambahan (kosong)
);