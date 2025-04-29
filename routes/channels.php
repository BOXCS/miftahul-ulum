<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{id_staf}.{id_ortu}', function ($user, $id_staf, $id_ortu) {
    // Auth check bisa kamu sesuaikan, contoh:
    return true; // Atau pastikan hanya staff yang boleh lihat jika $user->id == $id_staf
});
