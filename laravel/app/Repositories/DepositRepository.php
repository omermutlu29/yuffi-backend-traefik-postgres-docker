<?php


namespace App\Repositories;


use App\Interfaces\IDepositRepository;
use App\Models\BabySitterDeposit;

class DepositRepository implements IDepositRepository
{
    private $babySitterDeposit;

    public function __construct(BabySitterDeposit $babySitterDeposit)
    {
        $this->babySitterDeposit = $babySitterDeposit;
    }

    public function create(array $data): BabySitterDeposit
    {
        return $this->babySitterDeposit->create($data);
    }

    public function update(BabySitterDeposit $id, array $data): BabySitterDeposit
    {
        $babySitterDeposit = $this->babySitterDeposit->find($id);
        return $babySitterDeposit->update($data);
    }
}
