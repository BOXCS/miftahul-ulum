<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'id_staf';

    protected $fillable = ['id_akun', 'nama', 'alamat', 'no_telp', 'jabatan', 'tgl_bergabung'];

    public function akun(): HasOne
    {
        return $this->hasOne(Akun::class, 'id_akun', 'id_akun');
    }
}

