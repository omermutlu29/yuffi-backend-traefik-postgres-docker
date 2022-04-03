<?php

use App\Models\Parents;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Parent.{id}', function (\App\Models\Parents $user, $id) {
    if ($user instanceof \App\Models\Parents) {
        return (int)$user->id === (int)$id;
    }
    return false;
});
Broadcast::channel('App.Models.BabySitter.{id}', function (\App\Models\BabySitter $user, $id) {
    if ($user instanceof \App\Models\BabySitter) {
        return (int)$user->id === (int)$id;
    }
    return false;
});

