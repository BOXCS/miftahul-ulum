<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri'; // Nama tabel

    protected $fillable = [
        'nama_lengkap',
        'tahun_angkatan',
        'nama_orang_tua',
        'status',
        'sidik_jari',
    ];
}
