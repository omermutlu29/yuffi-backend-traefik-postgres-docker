<?php

use Illuminate\Support\Facades\Route;

Route::any('login',function (){
    return response()->json([
        'status'=>'Unauthenticated'
    ]);
})->name('login');

Route::get('/',function (){
});



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::any('broadcasting/auth',function (\Illuminate\Http\Request $request){
   dd($request->all());
});
