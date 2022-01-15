<?php


namespace App\Interfaces\PaymentInterfaces;


interface ICardStoreService
{
    public function storeCard(array $cardInformation);

    public function deleteCard();
}
