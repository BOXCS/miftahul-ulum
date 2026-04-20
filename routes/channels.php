<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel("App.Models.User.{id}", function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel("chat.{parentId}", function ($user, $parentId) {
    // For now, allow admin to access any chat.
    // In production, check if user is admin or the parent.
    return true;
});
