<?php

use Illuminate\Support\Facades\Route;

Route::any('login', function () {
    return response()->json([
        'status' => 'Unauthenticated'
    ]);
})->name('login');

Route::get('/', function () {
    return abort(403);
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
