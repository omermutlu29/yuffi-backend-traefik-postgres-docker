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
        return self::getUserById($id)->update($data);
    }

    public function getUserById(int $id)
    {
        return Parents::find($id);
    }

    public function getUserWithRelations(int $id, array $relations): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return Parents::with($relations)->find($id);
    }
}
