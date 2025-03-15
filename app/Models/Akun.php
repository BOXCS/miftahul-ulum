<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Akun extends Authenticatable
{
    use Notifiable;

    protected $table = 'akun'; // Sesuai dengan nama tabel di database
    protected $primaryKey = 'id_akun'; // Primary key sesuai migration

    protected $fillable = ['username', 'password', 'hak_akses'];

    protected $hidden = ['password'];

    public function getAuthPassword()
    {
        return $this->password;
    }
}
