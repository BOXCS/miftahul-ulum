<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\WaktuShalatHelper;

class Kehadiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
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