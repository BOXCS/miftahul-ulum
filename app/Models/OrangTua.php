<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tua';
    protected $primaryKey = 'id_ortu';

    protected $fillable = ['nama_lengkap', 'id_akun', 'alamat', 'no_telp'];

    // create relationship one to many for ortu->santri
    public function santri(): HasMany
    {
        return $this->hasMany(Santri::class, 'id_ortu', 'id_ortu');
    }
    public function akun(): HasOne
    {
        return $this->hasOne(Akun::class, 'id_akun', 'id_akun');
    }

    public function akun()
{
    return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
}

}

