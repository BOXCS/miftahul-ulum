<?php


// app/Models/Pengumuman.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';
    protected $primaryKey = 'id_pengumuman'; // <--- Ini penting!

    protected $fillable = [
        'judul',
        'isi',
        'tgl_mulai',
        'tgl_selesai',
        'kategori',
        'foto',
        'id_akun',
    ];
}


