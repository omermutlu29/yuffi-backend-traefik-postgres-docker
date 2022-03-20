<?php


namespace App\Interfaces\IServices;


interface IChangableActiveStatus
{
    public function changeActiveStatus(int $userId);
}
