<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Autoloader untuk PhpSpreadsheet tanpa Composer
 */
class PhpSpreadsheetAutoloader
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Fungsi autoload untuk memuat kelas PhpSpreadsheet
     *
     * @param string $class Nama kelas yang akan dimuat
     */
    public function autoload($class)
    {
        // Namespace prefix untuk PhpSpreadsheet
        $prefix = 'PhpOffice\\PhpSpreadsheet\\';

        // Direktori dasar untuk namespace prefix
        $base_dir = APPPATH . 'third_party/PhpSpreadsheet/src/PhpSpreadsheet/';

        // Periksa apakah kelas menggunakan namespace prefix
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // Jika tidak, lewati autoload ini
            return;
        }

        // Dapatkan nama kelas relatif
        $relative_class = substr($class, $len);

        // Ganti namespace separator dengan directory separator, tambahkan .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // Jika file ada, include
        if (file_exists($file)) {
            require_once $file;
        }
    }
}
