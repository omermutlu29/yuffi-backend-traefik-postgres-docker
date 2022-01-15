<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function (\App\Interfaces\PaymentInterfaces\IPayableToSubmerchant $paymentService) {
    $cardInformation = [
        "cardHolderName" => "ÖMER MUTLU",
        "cardNumber" => "5890040000000016",
        "expireMonth" => "12",
        "expireYear" => "24",
        "cvc" => "123",
    ];
    $products = [
        [
            "id" => 1,
            "name" => 'Bakım Hizmeti',
            "category" => 'Çocuk Bakım',
            "type" => 'VIRTUAL',
            "price" => 24.90
        ]
    ];
    $addressInformation = [];
    $addressInformation['name_surname'] = "Ömer MUTLU";
    $addressInformation['city'] = "İstanbul";
    $addressInformation['country'] = "Türkiye";
    $addressInformation['address'] = "Hürryet";
    $addressInformation['zip_code'] = "34520";


    $buyerInformation = [];
    $buyerInformation['id'] = 1;
    $buyerInformation['name'] = "Ömer";
    $buyerInformation['surname'] = "MUTLU";
    $buyerInformation['phoneNumber'] = "5415611003";
    $buyerInformation['email'] = "omermutlu29@gmail.com";
    $buyerInformation['identity'] = "28201740396";
    $buyerInformation['last_login'] = now();
    $addressInformation['city'] = "İstanbul";
    $addressInformation['country'] = "Türkiye";
    $addressInformation['zip_code'] = "34520";

    return $paymentService->payToSubMerchant(
        $cardInformation,
        $products,
        $addressInformation,
        $buyerInformation,
        24.90,
        'TR',
        1,
        1,
        'V56CquEYm9Uz++rSXvpoB0yKOTg=',
        20
    );
});


