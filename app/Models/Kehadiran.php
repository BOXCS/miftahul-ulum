<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\WaktuShalatHelper;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';

    // Kehadiran.php
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }

    protected $fillable = [
        'id_santri',
        'nama_santri',
        'jam_masuk',
        'jam_keluar',
        'status',
        'waktu_shalat',
        'tanggal_waktu',
    ];

    protected static function boot()
    {
        parent::boot();

        // Menentukan waktu shalat sebelum data disimpan
        static::creating(function ($model) {
            $model->waktu_shalat = WaktuShalatHelper::getWaktuShalat($model->jam_masuk);
        });
    }
}
