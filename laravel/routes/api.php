<?php
Route::get('test-chat',function (){
   dispatch(new \App\Events\NewAppointmentMessageEvent('Selam',34));
});
Route::prefix('fill')->group(function () {
    Route::get('towns/{city}', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getTowns']);
    Route::get('child-genders', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getChildGenders']);
    Route::get('child-years', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getChildYears']);
    Route::get('locations', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getLocations']);
    Route::get('genders', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getGenders']);
    Route::get('all/{city}', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getAll']);
    Route::get('nextDays', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getNextDays']);
    Route::get('hours', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getHours']);
    Route::get('times', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getTimes']);
    Route::get('child-count', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getChildCount']);
    Route::get('talents', [\App\Http\Controllers\API\BabySitter\Preferences\FillController::class, 'getTalents']);
});//Bitti

