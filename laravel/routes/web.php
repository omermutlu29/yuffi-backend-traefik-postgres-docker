<?php

use Illuminate\Support\Facades\Route;

Route::any('login',function (){
    return response()->json([
        'status'=>'Unauthenticated'
    ]);
})->name('login');

Route::get('/',function (){
    phpinfo();
});

