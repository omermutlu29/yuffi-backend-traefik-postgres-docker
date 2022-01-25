<?php


namespace App\Interfaces\PaymentInterfaces;


interface IThreeDPaymentToSubMerchant
{
    public function initializeThreeDForSubMerchant(
        array $cardInformation,
        array $products,
        array $addressInformation,
        array $buyerInformation,
        float $totalPrice,
        string $currency,
        int $installment,
        int $conversationId,
        string $subMerchant,
        float $subMerchantPrice,
        string $callbackUrl
    );
}
