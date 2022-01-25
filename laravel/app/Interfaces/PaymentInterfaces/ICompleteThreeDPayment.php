<?php


namespace App\Interfaces\PaymentInterfaces;


use Iyzipay\Model\ThreedsPayment;

interface ICompleteThreeDPayment
{
    public function completeThreeDPayment(string $conversationId, string $paymentId, ?string $conversationData) : ThreedsPayment;
}
