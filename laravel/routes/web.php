<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::any('login', function () {
    return response()->json([
        'status' => 'Unauthenticated'
    ]);
})->name('login');

Route::get('/', function (\App\Services\Appointment\AppointmentService $appointmentService) {
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
