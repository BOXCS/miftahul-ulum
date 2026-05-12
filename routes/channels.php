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

// Channel admin untuk notifikasi izin baru dari mobile
Broadcast::channel("permissions-admin", function ($user) {
    // Admin manapun yang ter-authentikasi via session boleh subscribe
    return $user !== null;
});

// Channel privat per santri — dipakai mobile (auth via /api/broadcasting/auth)
// Definisi di sini hanya untuk web auth (kalau dipakai dari panel admin)
Broadcast::channel("santri.{studentId}", function ($user, $studentId) {
    return $user !== null;
});

// Channel admin untuk hasil scan IoT (enrollment & absensi)
Broadcast::channel("iot-admin", function ($user) {
    return $user !== null;
});
