<?php


namespace App\Interfaces\PaymentInterfaces;


interface IPayToSubMerchantService
{
    public function payToSubMerchant(
        array $cardInformation,
        array $products,
        array $addressInformation,
        array $buyerInformation,
        float $totalPrice,
        string $currency,
        int $installment,
        int $conversationId,
        string $subMerchant,
        float $subMerchantPrice
    );
}
