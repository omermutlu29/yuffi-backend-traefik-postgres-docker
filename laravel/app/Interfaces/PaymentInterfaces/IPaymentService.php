<?php


namespace App\Interfaces\PaymentInterfaces;


interface IPaymentService
{
    /**
     * @param array $cardInformation example : ['cardHolderName','cardNumber','expireMonth','expireYear','cvc']
     * @param array $products example ['id','name','category','price']
     * @param array $addressInformation example ['contact_name','city','country','zip_code']
     * @param array $buyerInformation example ['id','name','surname','phoneNumber','email','identity','address','city','country','zip_code']
     * @param float $totalPrice example 25.90
     * @param string $currency example : TRY
     * @param int $installment example : 1
     * @param int $conversationId example : 2 orderId suggested
     * @return mixed
     */
    public function pay(
        array $cardInformation,
        array $products,
        array $addressInformation,
        array $buyerInformation,
        float $totalPrice,
        string $currency,
        int $installment,
        int $conversationId
    );

}
