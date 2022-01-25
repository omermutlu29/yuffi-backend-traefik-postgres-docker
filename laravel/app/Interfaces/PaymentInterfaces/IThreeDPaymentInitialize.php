<?php


namespace App\Interfaces\PaymentInterfaces;


interface IThreeDPaymentInitialize
{
    public function initializeThreeDPayment(
        array $cardInformation,
        array $products,
        array $addressInformation,
        array $buyerInformation,
        float $totalPrice,
        string $currency,
        int $installment,
        int $conversationId,
        string $callbackUrl
    );

}
