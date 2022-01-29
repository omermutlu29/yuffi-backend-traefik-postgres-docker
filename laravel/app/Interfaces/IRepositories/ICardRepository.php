<?php


namespace App\Interfaces\IRepositories;


interface ICardRepository
{
    public function store(int $userId, array $data);

    public function delete(string $cardToken);

    public function getUserKey(int $userId);

    public function getUserCardByCardToken(string $cardToken);
}
