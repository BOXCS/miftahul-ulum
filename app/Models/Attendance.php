<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = "attendance";

    protected $fillable = [
        "student_id",
        "tanggal",
        "jam_masuk",
        "jam_keluar",
        "waktu_shalat",
        "status",
        "keterangan",
    ];

    protected $casts = [
        "tanggal" => "date",
        "jam_masuk" => "datetime",
        "jam_keluar" => "datetime",
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
