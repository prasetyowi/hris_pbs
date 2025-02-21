<?php

use \PhpOffice\PhpSpreadsheet\Shared\Date;

if (!function_exists('checekFormatDatetime')) {
    function checekFormatDatetime($date)
    {
        $result = '';
        if (is_numeric($date)) {
            // Jika berupa angka, ubah ke format `Y-m-d H:i:s` atau `Y-m-d` tergantung nilai aslinya
            $dateObj = Date::excelToDateTimeObject($date);
             $result = (strpos($dateObj->format('H:i:s'), '00:00:00') !== false)
                ? $dateObj->format('Y-m-d')  // Jika jam 00:00:00, hanya ambil tanggal
                : $dateObj->format('Y-m-d H:i:s'); // Jika ada jam, gunakan format lengkap
        } elseif (\DateTime::createFromFormat('Y-m-d H:i:s', $date)) {
            // Jika sudah dalam format `Y-m-d H:i:s`, biarkan
             $result = $date;
        } elseif (\DateTime::createFromFormat('Y-m-d', $date)) {
            // Jika dalam format `Y-m-d`, tetap gunakan tanpa menambah waktu
             $result = $date;
        } else {
            // Jika format tidak valid, kosongkan atau beri pesan error
             $result = ''; // Bisa diganti dengan pesan error jika diperlukan
        }

        return  $result;
    }
}
