<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';
    protected $primaryKey = 'id_message';

    protected $fillable = [
        'id_session',
        'pengirim',
        'pesan',
        'waktu',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    // Relasi ke sesi chat
    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'id_session', 'id_session');
    }
}
