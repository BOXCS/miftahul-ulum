<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri'; // Nama tabel

    protected $fillable = [
        'nama',
        'tahun_angkatan',
        'nama_orang_tua',
        'status',
        'sidik_jari',
    ];
    protected $casts = [
        'id' => 'string',
    ];

    // create relationship one to many (inverse)/Belongs to for santri->ortu
    public function ortu(): BelongsTo  
    {
        return $this->belongsTo(OrangTua::class, 'id_ortu');
    }
}
