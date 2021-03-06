<?php
Route::prefix('baby-sitter')->group(function () {

    Route::prefix('login')->group(function () {
        Route::post('user', [\App\Http\Controllers\API\BabySitter\Auth\LoginController::class, 'loginOne']);
        Route::post('sms-code', [\App\Http\Controllers\API\BabySitter\Auth\LoginController::class, 'loginTwo']);
    });//Bitti
    Route::prefix('profile')->group(function () {
        Route::post('store', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'storeGeneralInformation']);
        Route::put('update-iban', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'updateIban']);
        Route::get('get-profile', [\App\Http\Controllers\API\BabySitter\Auth\ProfileController::class, 'getProfile']);
    });//Bitti
    Route::prefix('preferences')->group(function () {
        Route::post('update/personal', [\App\Http\Controllers\API\BabySitter\Preferences\PreferenceController::class, 'update']);
    });//Bitti
    Route::prefix('calendar')->group(function () {
        Route::post('add', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'store']);
        Route::put('update/{babySitterAvaliableTime}', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'update']);
        Route::delete('delete/{babySitterAvaliableTime}/', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'delete']);
        Route::get('get', [\App\Http\Controllers\API\BabySitter\Preferences\CalendarController::class, 'get']);
    });//Bitti

    Route::prefix('deposit')->group(function () {
        Route::get('debt', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'deposit']);//Bitti
        Route::post('pay', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'depositPay']);//Bitti
        Route::post('pay_3d', [\App\Http\Controllers\API\BabySitter\Deposit\DepositController::class, 'depositPay3d']);//Bitti
        Route::post('pay_3d_complete', [\App\Http\Controllers\API\BabySitter\Deposit\PaymentController::class, 'pay_3d_complete']);//Bitti Test Edilecek
    });//Bitti

    Route::prefix('fill')->group(function () {
        Route::get('towns/{city}', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getTowns']);
        Route::get('child-genders', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getChildGenders']);
        Route::get('child-years', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getChildYears']);
        Route::get('locations', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getLocations']);
        Route::get('genders', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getGenders']);
        Route::get('all/{city}', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getAll']);
        Route::get('nextDays', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getNextDays']);
    });//Bitti

    Route::prefix('message')->group(function () {
        Route::post('send/{appointment}', [\App\Http\Controllers\API\BabySitter\Message\MessageController::class, 'sendMessage']);
        Route::get('get/{appointment}', [\App\Http\Controllers\API\BabySitter\Message\MessageController::class, 'getMessages']);
    });
    Route::prefix('payment')->group(function () {
        Route::get('success', [\App\Http\Controllers\API\BabySitter\Payment\PaymentController::class, 'index']);
        Route::get('all', [\App\Http\Controllers\API\BabySitter\Payment\PaymentController::class, 'all']);
    });
    Route::get('appointments', [\App\Http\Controllers\API\BabySitter\Appointment\AppointmentController::class, 'index']);
});
