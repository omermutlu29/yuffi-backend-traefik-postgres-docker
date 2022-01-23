<?php


namespace App\Interfaces\PaymentInterfaces;


interface IRegisterCardService
{
    public function createCard(string $cardUserKey,array $cardData);

    public function createCardWithUser(array $cardData,string $email, string $externalId);

    public function getCardList(string $cardUserKey);

    public function deleteCard(string $cardUserKey, string $cardToken);
}
