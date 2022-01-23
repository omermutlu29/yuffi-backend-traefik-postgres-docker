<?php


namespace App\Interfaces\IRepositories;


interface ICardRepository
{
    public function store(int $userId, array $data);

    public function delete(int $cardId);

    public function getUserKey(int $userId);
}
