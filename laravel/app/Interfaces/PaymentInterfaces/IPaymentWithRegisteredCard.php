<?php


namespace App\Interfaces\PaymentInterfaces;


interface IPaymentWithRegisteredCard
{

    public function payWithRegisteredCardForVirtualProducts(
        string $cardToken,
        string $cardUserKey,
        array $products,
        array $addressInformation,
        array $buyerInformation,
        float $totalPrice,
        int $conversationId);
}
