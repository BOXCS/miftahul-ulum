<?php


// app/Models/Pengumuman.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
    public function staff(): HasOneThrough
    {
        return $this->hasOneThrough(
            Staff::class,
            Akun::class,
            'id_akun', // foreign key di tabel guru yang menunjuk ke akun
            'id_akun', // foreign key di tabel tugas yang menunjuk ke akun
            'id_akun', // local key di tabel tugas
            'id_akun'  // local key di tabel akun
        );
    }
    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }
}


