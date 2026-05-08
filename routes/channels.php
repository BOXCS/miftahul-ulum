<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ParentModel;

// Channel default Laravel — biarkan
Broadcast::channel("App.Models.User.{id}", function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel("chat.{parentId}", function ($user, $parentId) {
    // Admin dan superadmin boleh akses semua chat
    if (in_array($user->role, ['admin', 'superadmin'])) {
        return true;
    }

    // Orang tua hanya boleh akses room miliknya
    return ParentModel::where('user_id', $user->id)
        ->where('id', $parentId)
        ->exists();
});
