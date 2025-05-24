<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Akun extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'akun';
    protected $primaryKey = 'id_akun';

    protected $fillable = ['username', 'email', 'password', 'hak_akses'];

    protected $hidden = ['password'];

    public function ortu():HasOne
    {
        return $this->hasOne(OrangTua::class, 'id_akun', 'id_akun');
    }
    public function staff():HasOne
    {
        return $this->hasOne(Staff::class, 'id_akun', 'id_akun');
    }
}

