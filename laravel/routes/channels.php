<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Parent.{id}', function (\App\Models\Parents $user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::channel('App.Models.BabySitter.{id}', function (\App\Models\BabySitter $user, $id) {
    return (int)$user->id === (int)$id;
});

