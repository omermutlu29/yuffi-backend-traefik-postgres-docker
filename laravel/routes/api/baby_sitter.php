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


Route::get('/push',function (){
    $data = array(
        'body' => "YUNUS",
        'title' => "SELAM",
        'playSound' => true,
        'soundName' => 'default'
    );
    $to = "dfX3BZEET6OZrx46CrzG7W:APA91bEEOCHmClEPLqVpz7zqfy2lu4HvAxLVgR-BKOMW8bpi06P628u9r0MXfxv0hrZwswSGlUc_KdKmoN6KXrRlqYZNqVeha2KbYlRqbpJ0Ths6jhO570wV6rBGsbXFSaWFHkprH3Wp";
    $apiKey = "AAAAFVAfmdM:APA91bGRh7CzP9EPBdeHpG0zM3BB_6wuRL21atJryqFMoyMrhZAk8sr8w0Vma-g2HJcN3zjQts5ukEfoUZ96zI-PBD-UW_zNI5FPI0gwNHzoXbqKoUUoittj78knSVILk7aFkm3xLrSK";
    $fields = array('to' => $to, 'notification' => $data);
    $headers = array('Authorization: key=' . $apiKey, 'Content-Type: application/json');
    $url = 'https://fcm.googleapis.com/fcm/send';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);

    curl_close($ch);
    print_r(json_decode($result, true));
});
