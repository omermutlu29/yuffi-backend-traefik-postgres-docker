<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\IUserRepository;
use App\Models\Parents;

class ParentRepository implements IUserRepository
{
    public function getUserByPhone(string $phone)
    {
        return Parents::phone($phone)->first();
    }

    public function save_sms_code($id, $code)
    {
        return Parents::find($id)->sms_codes()->create([
            'code' => $code,
        ]);
    }

    public function get_last_sms_code($id, $code)
    {
        return Parents::find($id)->sms_codes()->where('code', $code)->first();
    }

    public function create(array $data)
    {
        return Parents::create($data);
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }
}
