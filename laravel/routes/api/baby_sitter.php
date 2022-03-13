<?php
Route::prefix('baby-sitter')->group(function () {

    Route::prefix('login')->group(function () {
        Route::post('user', [\App\Http\Controllers\API\BabySitter\Auth\LoginController::class, 'loginOne']);
        Route::post('sms-code', [\App\Http\Controllers\API\BabySitter\Auth\LoginController::class, 'loginTwo']);
    });//Bitti


    Route::prefix('profile')->group(function () {
        Route::post('store', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'storeGeneralInformation']);
        Route::post('update', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'updateGeneralInformation']);
        //Route::put('update-iban', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'updateIban']);
        Route::get('get-profile', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'getProfile']);
    });//Bitti

    Route::prefix('preferences')->group(function () {
        Route::post('update', [\App\Http\Controllers\API\BabySitter\Preferences\PreferenceController::class, 'update']);
    });

    Route::prefix('calendar')->group(function () {
        Route::post('add', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'store']);
        Route::put('update/{babySitterAvailableTime}', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'update']);
        Route::delete('delete', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'delete']);
        Route::get('index', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'index']);
    });//Bitti

    Route::prefix('appointment')->group(function () {
        Route::get('detail/{appointment}', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'getAppointmentDetail']);
        Route::get('get/future', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'getFutureAppointments']);
        Route::get('get/past', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'getPastAppointments']);
        Route::put('disapprove', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'disapprove']);
        //Route::get('my-approved-appointments', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'myApprovedAppointments']);
        //Route::get('my-not-approved-appointments', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'myNotApprovedAppointments']);
        //Route::get('my-paid-appointments', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'myPaidAppointments']);
        //Route::get('my-pending-appointments', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'myPendingPaymentAppointments']);
        //Route::put('approve', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'approve']);
    });

    //Test edilecek
    Route::prefix('message')->group(function () {
        Route::post('send/{appointment}', [\App\Http\Controllers\API\BabySitter\Message\MessageController::class, 'sendMessage']);
        Route::get('get/{appointment}', [\App\Http\Controllers\API\BabySitter\Message\MessageController::class, 'getMessages']);
    });


    /*Route::prefix('deposit')->group(function () {
        Route::get('debt', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'deposit']);//Bitti
        Route::post('pay', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'pay']);//Bitti
        Route::post('pay-3d', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'depositPay3d']);//Bitti
        Route::post('pay-3d-complete', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'threeDComplete'])->name('babysitter.deposit.callback');//Bitti Test Edilecek
    });//Bitti*/


});
