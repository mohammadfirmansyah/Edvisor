<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('formatTime')) {
    /**
     * Memformat waktu notifikasi sesuai dengan aturan yang ditetapkan, 
     * menggunakan nama bulan dalam bahasa Indonesia.
     *
     * @param string $timestamp Waktu notifikasi dalam format 'Y-m-d H:i:s'
     * @return string Waktu yang telah diformat
     */
    function formatTime($timestamp)
    {
        // Definisikan nama bulan dalam bahasa Indonesia
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $notifTime = new DateTime($timestamp);
        $currentTime = new DateTime();
        $diff = $currentTime->diff($notifTime);

        // Hitung selisih waktu
        $diffInMinutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
        $diffInHours = ($diff->days * 24) + $diff->h;

        // Format untuk selisih waktu
        if ($diffInHours < 1) {
            return $diff->i . ' menit yang lalu';
        } elseif ($diffInHours < 24) {
            return $diff->h . ' jam yang lalu';
        } else {
            // Format waktu lengkap dengan nama bulan dalam bahasa Indonesia
            $day = $notifTime->format('d');
            $month = (int) $notifTime->format('m');
            $year = $notifTime->format('Y');
            $time = $notifTime->format('H:i');
            
            return $time . ' ' . $day . ' ' . $bulanIndonesia[$month] . ' ' . $year;
        }
    }
}
