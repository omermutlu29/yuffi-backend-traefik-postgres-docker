<?php
Route::prefix('baby-sitter')->group(function () {

    Route::prefix('deposit')->group(function () {
        Route::get('debt', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'deposit']);//Bitti
        Route::post('pay', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'pay']);//Bitti
        Route::post('pay-3d', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'depositPay3d']);//Bitti
        Route::post('pay-3d-complete', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'threeDComplete'])->name('babysitter.deposit.callback');//Bitti Test Edilecek
    });//Bitti

    Route::prefix('login')->group(function () {
        Route::post('user', [\App\Http\Controllers\API\BabySitter\Auth\LoginController::class, 'loginOne']);
        Route::post('sms-code', [\App\Http\Controllers\API\BabySitter\Auth\LoginController::class, 'loginTwo']);
    });//Bitti

    Route::prefix('profile')->group(function () {
        Route::post('store', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'storeGeneralInformation']);
        //Route::put('update-iban', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'updateIban']);
        Route::get('get-profile', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'getProfile']);
    });//Bitti

    Route::prefix('preferences')->group(function () {
        Route::post('update', [\App\Http\Controllers\API\BabySitter\Preferences\PreferenceController::class, 'update']);
    });


    Route::prefix('fill')->group(function () {
        Route::get('towns/{city}', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getTowns']);
        Route::get('child-genders', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getChildGenders']);
        Route::get('child-years', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getChildYears']);
        Route::get('locations', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getLocations']);
        Route::get('genders', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getGenders']);
        Route::get('all/{city}', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getAll']);
        Route::get('nextDays', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getNextDays']);
    });//Bitti

    Route::prefix('calendar')->group(function () {
        Route::post('add', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'store']);
        Route::put('update/{babySitterAvailableTime}', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'update']);
        Route::delete('delete/{babySitterAvailableTime}/', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'delete']);
        Route::get('index', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'index']);
    });//Bitti

    Route::prefix('appointments')->group(function () {
        Route::get('my-approved-appointments', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'myApprovedAppointments']);
        Route::get('my-not-approved-appointments', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'myNotApprovedAppointments']);
        Route::get('my-paid-appointments', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'myPaidAppointments']);
        Route::get('my-pending-appointments', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'myPendingPaymentAppointments']);
        Route::put('approve', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'approve']);
        Route::put('disapprove', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'disapprove']);
    });


});
