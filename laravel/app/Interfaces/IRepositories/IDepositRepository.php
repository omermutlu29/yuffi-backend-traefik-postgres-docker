<?php


namespace App\Interfaces\IRepositories;


use App\Models\BabySitterDeposit;

interface IDepositRepository
{
    public function create(array $data);
    public function update(BabySitterDeposit $id, array $data);
}
