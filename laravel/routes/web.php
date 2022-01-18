<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    phpinfo();
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
