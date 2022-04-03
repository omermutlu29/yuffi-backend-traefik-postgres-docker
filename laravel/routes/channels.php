<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Parent.{id}', function ($user, $id) {
    $parent = \App\Models\Parents::find($user->id);
    return (int)$parent->id === (int)$id;
});
Broadcast::channel('App.Models.BabySitter.{id}', function ($user, $id) {
    $babySitter = \App\Models\BabySitter::find($user->id);
    return (int)$babySitter->id === (int)$id;
});

