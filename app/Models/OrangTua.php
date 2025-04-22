<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tua';
    protected $primaryKey = 'id_ortu';

    protected $fillable = ['id_akun', 'alamat', 'no_telp'];
}

