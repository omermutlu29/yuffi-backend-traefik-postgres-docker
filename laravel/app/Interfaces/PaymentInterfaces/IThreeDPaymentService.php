<?php


namespace App\Interfaces\PaymentInterfaces;


interface IThreeDPaymentService
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


    public function completeThreeDPayment(string $conversationId, string $paymentId, ?string $conversationDat);
}
