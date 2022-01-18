<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\IUserRepository;
use App\Models\BabySitter;

class BabySitterRepository implements IUserRepository
{
    public function getUserByPhone(string $phone)
    {
        return BabySitter::phone($phone)->first();
    }

    public function save_sms_code($id, $code)
    {
        return BabySitter::find($id)->sms_codes()->create([
            'code' => $code,
        ]);
    }

    public function get_last_sms_code($id, $code)
    {
        return BabySitter::find($id)->sms_codes()->where('code', $code)->first();
    }

    public function create(array $data)
    {
        return BabySitter::create($data);
    }

    public function update(int $id, array $data)
    {
        return BabySitter::find($id)->update($data);
    }
}
