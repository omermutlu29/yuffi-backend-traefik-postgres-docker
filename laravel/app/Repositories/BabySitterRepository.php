<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Models\BabySitter;

class BabySitterRepository implements IUserRepository, IBabySitterRepository
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

    public function getUserWithRelations(int $id, array $relations): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return BabySitter::with($relations)->findOrFail($id);
    }

    public function updateAcceptedLocations(BabySitter $babySitter, array $acceptedLocations)
    {
        $babySitter->accepted_locations()->sync($acceptedLocations);
    }

    public function updateAvailableTowns(BabySitter $babySitter, array $availableTowns)
    {
        $babySitter->accepted_locations()->sync($availableTowns);
    }
}
