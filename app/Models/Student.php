<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        "parent_id",
        "name",
        "nis",
        "gender",
        "tanggal_lahir",
        "email",
        "phone",
        "class",
        "tahun_angkatan",
        "address",
        "status",
        "foto",
        "fingerprint_template",
        "fingerprint_quality",
        "scanned_at",
    ];

    protected $casts = [
        "tanggal_lahir" => "date",
        "created_at" => "datetime",
        "updated_at" => "datetime",
        "scanned_at" => "datetime",
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, "parent_id");
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
