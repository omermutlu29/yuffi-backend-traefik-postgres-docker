<?php


namespace App\Interfaces\DepositService;

use App\Models\BabySitter;

interface IDeposit
{
    public function pay(BabySitter $babySitter, array $cardInformation);
}
