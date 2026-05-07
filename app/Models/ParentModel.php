<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = "parents";

    protected $fillable = [
        "user_id",
        "name",
        "relationship",
        "phone",
        "email",
        "address",
        "password",  // Menambahkan password ke $fillable
        "role",      // Menambahkan role ke $fillable
    ];
    protected $hidden = [
        "password",
        "remember_token",
    ];

    protected $casts = [
        "password" => "hashed",
    ];
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Students
    public function students()
    {
        return $this->hasMany(Student::class, "parent_id");
    }

    // Relasi ke ChatMessage
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, "parent_id");
    }
}