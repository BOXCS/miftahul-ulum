<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $table = 'chat_sessions';
    protected $primaryKey = 'id_session';

    protected $fillable = [
        'id_staf',
        'id_ortu',
        'last_message',
        'last_message_time',
        'unread_count',
    ];

    // Relasi ke staf
    public function staf()
    {
        return $this->belongsTo(Staff::class, 'id_staf', 'id_staf');
    }

    // Relasi ke orang tua
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'id_ortu', 'id_ortu');
    }

    // Relasi ke pesan-pesan
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'id_session', 'id_session');
    }
}
