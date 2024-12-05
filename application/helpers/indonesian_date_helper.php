<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Fungsi untuk mengubah nama bulan dan hari ke dalam bahasa Indonesia
 * @param string $date_string Tanggal dalam format apapun yang dikenali oleh strtotime()
 * @param bool $with_day Jika true, akan menyertakan nama hari
 * @return string Tanggal dengan nama bulan dan hari dalam bahasa Indonesia
 */
if (!function_exists('indonesian_date')) {
    function indonesian_date($date_string, $with_day = false) {
        // Daftar bulan dalam bahasa Indonesia
        $bulan_indonesia = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        // Daftar hari dalam bahasa Indonesia
        $hari_indonesia = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        if ($with_day) {
            $date = date('l / d F Y', strtotime($date_string));
            // Ganti nama hari dari bahasa Inggris ke bahasa Indonesia
            foreach ($hari_indonesia as $eng => $ind) {
                $date = str_replace($eng, $ind, $date);
            }
        } else {
            $date = date('d F Y', strtotime($date_string));
        }

        // Ganti nama bulan dari bahasa Inggris ke bahasa Indonesia
        foreach ($bulan_indonesia as $eng => $ind) {
            $date = str_replace($eng, $ind, $date);
        }

        return $date;
    }
}