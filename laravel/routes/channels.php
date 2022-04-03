<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::middleware('auth:parent')->channel('App.Models.Parent.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::middleware('auth:baby_sitter')->channel('App.Models.BabySitter.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

