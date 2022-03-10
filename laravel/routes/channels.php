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
Broadcast::routes(['middleware'=>['api']]);
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::channel('App.Models.Appointment.{id}', function ($user, $id) {
    $appointment = \App\Models\Appointment::find($id);
    if (!$appointment) {
        return false;
    }
    if ($user instanceof \App\Models\BabySitter) {
        return $appointment->baby_sitter_id == $user->id;
    }
    if ($user instanceof \App\Models\Parents) {
        return $appointment->parent_id == $user->id;
    }
    return false;
});
