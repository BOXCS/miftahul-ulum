<?php

namespace App\Helpers;

class ViteHelper
{
    public static function allJsFiles($directory = 'resources/js')
    {
        $files = glob(base_path($directory . '/*.js')); // Ambil semua file JS dalam folder
        return array_map(fn($file) => str_replace(base_path() . '/', '', $file), $files);
    }
}
