<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\IUserRepository;
use App\Models\Parents;
use App\Services\HttpStatuses\HttpStatuses;

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
        try {
            return $this->getUserById($id)->update($data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getUserById(int $id)
    {
        $user = Parents::find($id);
        if (!$user) throw new \Exception('User could not find', HttpStatuses::HTTP_BAD_REQUEST);
        return $user;
    }

    public function getUserWithRelations(int $id, array $relations)
    {
        return Parents::with($relations)->find($id);
    }
}
