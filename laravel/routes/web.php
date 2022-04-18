<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::any('login', function () {
    return response()->json([
        'status' => 'Unauthenticated'
    ]);
})->name('login');

Route::get('/', function () {
    \App\Models\Appointment::notificationDidNotSent()->get()->dd();
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
