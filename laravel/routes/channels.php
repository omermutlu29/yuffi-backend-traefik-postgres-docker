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
    return (int)$user->id === (int)$id;
});

Broadcast::channel('App.Models.Appointment.34', function () {
    return true;
    /*$appointment = \App\Models\Appointment::find($id);
    if (!$appointment) {
        return false;
    }
    if (typeOf($user) == \App\Models\BabySitter::class) {
        return $appointment->baby_sitter_id == $user->id;
    }
    if (typeOf($user) == \App\Models\Parents::class) {
        return $appointment->parent_id == $user->id;
    }
    return false;*/
});
