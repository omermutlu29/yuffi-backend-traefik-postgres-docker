<?php
Route::prefix('parent')->group(function () {
    Route::prefix('login')->group(function () {
        Route::post('user', [\App\Http\Controllers\API\Parent\Auth\LoginController::class, 'loginOne']);
        Route::post('sms-code', [\App\Http\Controllers\API\Parent\Auth\LoginController::class, 'loginTwo']);
    });//Bitti

    Route::prefix('profile')->group(function () {
        Route::put('update', [\App\Http\Controllers\API\Parent\Auth\RegisterController::class, 'updateInformation']);
        Route::get('get-profile', [\App\Http\Controllers\API\Parent\Auth\RegisterController::class, 'getProfile']);
    });//Bitti

    Route::resource('child', \App\Http\Controllers\API\Parent\Child\ChildController::class);//Bitti

    Route::post('filter', [\App\Http\Controllers\API\Parent\Filter\FilterController::class, 'filter']);

    Route::prefix('baby-sitter')->group(function () {
        Route::get('show/{babySitter}', [\App\Http\Controllers\API\Parent\Filter\BabySitterController::class, 'show'])->name('baby-sitter.show');
        Route::post('choose/{babySitter}', [\App\Http\Controllers\API\Parent\Filter\BabySitterController::class, 'choose'])->name('baby-sitter.choose');
    });

    Route::prefix('message')->group(function () {
        Route::post('send/{appointment}', [\App\Http\Controllers\API\Parent\Message\MessageController::class, 'sendMessage']);
        Route::get('get/{appointment}', [\App\Http\Controllers\API\Parent\Message\MessageController::class, 'getMessage']);
    });

    Route::prefix('card')->group(function () {
        Route::get('index', [\App\Http\Controllers\API\Parent\Card\CardController::class, 'cardList']);
        Route::post('store', [\App\Http\Controllers\API\Parent\Card\CardController::class, 'store']);
        Route::delete('delete/{id}', [\App\Http\Controllers\API\Parent\Card\CardController::class, 'delete'])->name('card.delete');
    });

    Route::get('appointments', [\App\Http\Controllers\API\Parent\Appointment\AppointmentController::class, 'index']);

});

