<?php

Route::post('tst', function (\App\Interfaces\PaymentInterfaces\IPayToSubMerchantService $paymentService) {
    $cardInformation = [
        'cardHolderName' => 'Ömer Yusuf MUTLU',
        'cardNumber' => '5890040000000016',
        'expireMonth' => '12',
        'expireYear' => '24',
        'cvc' => '123'
    ];

    $products = [['id' => 1, 'name' => 'Bakici', 'category' => 'Bakici', 'price' => '30.00']];
    $addressInformation = ['contact_name' => 'Ömer MUTLU', 'city' => 'İstanbul', 'country' => 'Türkiye', 'zip_code' => '34520','address'=>'Hürriyet mahallesi sedef sokak 3/11'];
    $buyerInformation = ['id' => 1, 'name' => 'Ömer', 'surname' => 'MUTLU', 'phoneNumber' => '5415611003', 'email' => 'omermutlu29@gmail.com', 'identity' => '28201740396', 'address' => 'Hürriyet mahallesi sedef sokak 3/11', 'city' => 'istanbul', 'country' => 'Türkiye', 'zip_code' => '34520'];
    $totalPrice = 30.0;
    $currency = 'TRY';
    $installment = 1;
    $conversationId = 1;
    dd($paymentService->payToSubMerchant($cardInformation, $products,$addressInformation,$buyerInformation,$totalPrice,$currency,$installment,$conversationId,'GG749dE/lrMFYBWjc8PRoBnVSM4=',$totalPrice-5));
});


