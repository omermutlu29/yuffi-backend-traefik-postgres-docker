<?php
Route::prefix('parent')->group(function () {
    //SOLID
    Route::prefix('login')->group(function () {
        Route::post('user', [\App\Http\Controllers\API\Parent\Auth\LoginController::class, 'loginOne']);
        Route::post('sms-code', [\App\Http\Controllers\API\Parent\Auth\LoginController::class, 'loginTwo']);
    });//Bitti


    Route::prefix('profile')->group(function () {
        Route::post('update', [\App\Http\Controllers\API\Parent\Auth\ProfileController::class, 'updateProfile']);
        Route::get('get-profile', [\App\Http\Controllers\API\Parent\Auth\ProfileController::class, 'getProfile']);
    });//Bitti

    Route::resource('child', \App\Http\Controllers\API\Parent\Child\ChildController::class);//Bitti

    Route::prefix('card')->group(function () {
        Route::get('index', [\App\Http\Controllers\API\Parent\Card\CardController::class, 'index']);
        Route::post('store', [\App\Http\Controllers\API\Parent\Card\CardController::class, 'store']);
        Route::delete('delete', [\App\Http\Controllers\API\Parent\Card\CardController::class, 'delete'])->name('card.delete');
    });


    Route::prefix('appointment')->group(function () {
        Route::post('{appointment}/pay-with-threeD', [\App\Http\Controllers\API\Parent\Appointment\AppointmentController::class, 'confirmAppointmentPayThreeD']);
        Route::post('{appointment}/pay', [\App\Http\Controllers\API\Parent\Appointment\AppointmentController::class, 'confirmAppointmentAndPay']);
        //ödeme sistemi için aşağıdaki route
        Route::post('threeD-complete', [\App\Http\Controllers\API\Parent\Appointment\AppointmentController::class, 'completeAppointmentPayThreeD'])->name('appointment.pay.complete');
        Route::post('create/{babySitter}', [\App\Http\Controllers\API\Parent\Filter\BabySitterController::class, 'makeOfferToBabySitter'])->name('baby-sitter.choose');
    });




    //Test edilecek
    Route::prefix('baby-sitter')->group(function () {
        Route::get('show/{babySitter}', [\App\Http\Controllers\API\Parent\Filter\BabySitterController::class, 'show'])->name('baby-sitter.show');
        Route::post('find-baby-sitter', [\App\Http\Controllers\API\Parent\Filter\BabySitterController::class, 'findBabySitter'])->name('baby-sitter.findBabySitter');
    });

    //Test edilecek
    Route::prefix('message')->group(function () {
        Route::post('send/{appointment}', [\App\Http\Controllers\API\Parent\Message\MessageController::class, 'sendMessage']);
        Route::get('get/{appointment}', [\App\Http\Controllers\API\Parent\Message\MessageController::class, 'getMessage']);
    });
    //solid ends


});

