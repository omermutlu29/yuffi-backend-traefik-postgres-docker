<?php


namespace App\Interfaces\PaymentInterfaces;


interface IPayment
{
    public function payToMerchant(
        array $cardInformation,
        array $products,
        array $addressInformation,
        array $buyerInformation,
        float $totalPrice,
        string $currency,
        int $installment,
        int $conversationId,
    );

    public static function generateBasketItemMerchant(
        int $basketItemId,
        string $basketItemName,
        string $basketItemCategory,
        float $basketItemPrice);

}
