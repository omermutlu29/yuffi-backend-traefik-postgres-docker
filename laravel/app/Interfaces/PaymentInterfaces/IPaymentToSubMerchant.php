<?php


namespace App\Interfaces\PaymentInterfaces;


interface IPaymentToSubMerchant
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
        float $subMerchantPrice,
    ): \Iyzipay\Model\Payment;
}
