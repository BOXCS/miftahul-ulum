<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tua';
    protected $primaryKey = 'id_ortu';

    protected $fillable = ['id_akun', 'alamat', 'no_telp'];

    // create relationship one to many for ortu->santri
    public function santri(): HasMany
    {
        return $this->hasMany(Santri::class, 'id_ortu');
    }
}

