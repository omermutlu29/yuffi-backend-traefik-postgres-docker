<?php
Route::prefix('baby-sitter')->group(function () {
    Route::get('logout', [\App\Http\Controllers\API\BabySitter\Auth\LogoutController::class, 'logout']);
    Route::prefix('login')->group(function () {
        Route::post('user', [\App\Http\Controllers\API\BabySitter\Auth\LoginController::class, 'loginOne']);
        Route::post('sms-code', [\App\Http\Controllers\API\BabySitter\Auth\LoginController::class, 'loginTwo']);
    });//Bitti


    Route::prefix('profile')->group(function () {
        Route::post('store', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'storeGeneralInformation']);
        Route::post('update', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'updateGeneralInformation']);
        //Route::put('update-iban', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'updateIban']);
        Route::get('get-profile', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'getProfile']);
        Route::post('change-active-status', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'changeActiveStatus']);
    });//Bitti

    Route::prefix('preferences')->group(function () {
        Route::post('update', [\App\Http\Controllers\API\BabySitter\Preferences\PreferenceController::class, 'update']);
    });

    //Test edildi onaylandı
    Route::prefix('message')->group(function () {
        Route::post('send/{appointment}', [\App\Http\Controllers\API\BabySitter\Message\MessageController::class, 'sendMessage']);
        Route::get('get/{appointment}', [\App\Http\Controllers\API\BabySitter\Message\MessageController::class, 'getMessages']);
    });

    Route::prefix('calendar')->group(function () {
        Route::post('add', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'store']);
        Route::delete('delete', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'delete']);
        Route::get('index', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'index']);
    });//Bitti

    Route::prefix('appointment')->group(function () {
        Route::get('detail/{appointment}', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'getAppointmentDetail']);
        Route::get('get/future', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'getFutureAppointments']);
        Route::get('get/upcoming', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'getUpcomingAppointments']);
        Route::get('get/past', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'getPastAppointments']);
        Route::post('cancel-appointment', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'disapprove']);
    });

    Route::get('faq', function () {
        return \App\Models\Faq::where('user_type', 'baby_sitter')->get();
    });

    Route::get('contract', function () {
        return \App\Models\Contract::where('user_type', 'baby_sitter')->get();
    });


});
