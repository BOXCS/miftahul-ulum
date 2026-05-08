<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel("App.Models.User.{id}", function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel("chat.{parentId}", function ($user, $parentId) {
    \Log::info('Broadcast auth attempt', [
        'user' => $user?->id,
        'parentId' => $parentId,
    ]);
    return true; // izinkan semua dulu untuk debug
});

