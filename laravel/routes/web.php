<?php

use Illuminate\Support\Facades\Route;

Route::any('login',function (){
    return response()->json([
        'status'=>'Unauthenticated'
    ]);
})->name('login');

Route::get('test',function (\App\Http\Requests\StoreAvailableTime $req){
    $cs=new \App\Services\Calendar\BabySitterCalendarService();
    return $cs->store($req->all());
});

