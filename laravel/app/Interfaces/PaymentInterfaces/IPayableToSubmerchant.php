<?php


namespace App\Interfaces\PaymentInterfaces;


interface IPayableToSubMerchant
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

    public static function generateBasketItemForSubMerchant(
        string $subMerchantKey,
        float $subMerchantPrice,
        int $basketItemId,
        string $basketItemName,
        string $basketItemCategory,
        float $basketItemPrice);
}
