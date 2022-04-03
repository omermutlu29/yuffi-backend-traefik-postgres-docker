<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Parent.{id}', function ($user, $id) {
    \Illuminate\Support\Facades\Log::info("parent".' '.$user.' '.$id);
    if ($user instanceof \App\Models\Parents) {
        return (int)$user->id === (int)$id;
    }
    return false;
});
Broadcast::channel('App.Models.BabySitter.{id}', function ($user, $id) {
    \Illuminate\Support\Facades\Log::info("baby_sitter".' '.$user.' '.$id);
    if ($user instanceof \App\Models\BabySitter) {
        return (int)$user->id === (int)$id;
    }
    return false;
});

