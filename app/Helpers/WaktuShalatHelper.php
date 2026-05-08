<?php

namespace App\Helpers;

class WaktuShalatHelper
{
    public static function getWaktuShalat($jamMasuk)
    {
        $jam = strtotime($jamMasuk);

        if ($jam >= strtotime('04:00:00') && $jam < strtotime('06:00:00')) {
            return 'Subuh';
        } elseif ($jam >= strtotime('12:00:00') && $jam < strtotime('14:00:00')) {
            return 'Dzuhur';
        } elseif ($jam >= strtotime('15:00:00') && $jam < strtotime('17:00:00')) {
            return 'Ashar';
        } elseif ($jam >= strtotime('18:00:00') && $jam < strtotime('19:00:00')) {
            return 'Maghrib';
        } elseif ($jam >= strtotime('19:30:00') && $jam < strtotime('21:00:00')) {
            return 'Isya';
        } else {
            return null; // Jika tidak masuk ke rentang waktu shalat
        }
    }
}