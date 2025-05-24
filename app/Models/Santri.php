<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri'; // Nama tabel
    protected $primaryKey = 'id_santri';
    protected $keyType = 'string';
    protected $fillable = [
        'id_santri',
        'nama',
        'tahun_angkatan',
        'sidik_jari',
        'status',
        'id_ortu',
    ];
    protected $casts = [
        'id' => 'string',
    ];

    // create relationship one to many (inverse)/Belongs to for santri->ortu
    public function ortu(): BelongsTo  
    {
        return $this->belongsTo(OrangTua::class, 'id_ortu', 'id_ortu');
    }
    public function kehadiran():HasMany
    {
        return $this->hasMany(Kehadiran::class, 'id_santri', 'id_santri');
    }
}