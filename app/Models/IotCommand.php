<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IotCommand extends Model
{
    protected $table = 'iot_commands';

    protected $fillable = [
        'type',          // 'enroll' | 'scan_request'
        'student_id',
        'fingerprint_id',
        'status',        // pending | in_progress | done | failed | expired
        'result',
        'processed_at',
    ];

    protected $casts = [
        'result'       => 'array',
        'processed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
