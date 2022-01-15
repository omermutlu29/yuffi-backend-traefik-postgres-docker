<?php


namespace App\Interfaces\PaymentInterfaces;


interface IThreeDPaymentService
{
    public function initializeThreeDPayment();

    public function completeThreeDPayment();
}
