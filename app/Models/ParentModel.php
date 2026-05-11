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
        "fcm_token",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, "parent_id");
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, "parent_id");
    }
}
