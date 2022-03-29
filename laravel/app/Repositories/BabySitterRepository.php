<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Models\BabySitter;
use App\Models\Parents;

class BabySitterRepository implements IUserRepository, IBabySitterRepository
{
    public function getUserByPhone(string $phone)
    {
        return BabySitter::phone($phone)->first();
    }

    public function save_sms_code($id, $code)
    {
        return self::getUserById($id)->sms_codes()->create([
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

    public function getUserWithRelations(int $id, array $relations)
    {
        return BabySitter::with($relations)->findOrFail($id);
    }

    public function updateAcceptedLocations(BabySitter $babySitter, array $acceptedLocations)
    {
        $babySitter->accepted_locations()->sync($acceptedLocations);
    }

    public function updateAvailableTowns(BabySitter $babySitter, array $availableTowns)
    {
        $babySitter->available_towns()->sync($availableTowns);
    }

    public function updateShareableTalents(BabySitter $babySitter, array $shareableTalents)
    {
        $babySitter->shareable_talents()->sync($shareableTalents);
    }

    public function removeShareableTalents(BabySitter $babySitter)
    {
        $babySitter->shareable_talents()->detach();
    }

    public function updateChildYears(BabySitter $babySitter, array $shareableTalents)
    {
        $babySitter->child_years()->sync($shareableTalents);

    }

    public function getUserById(int $id)
    {
        return BabySitter::find($id);
    }

    public function getSubMerchantId($id)
    {
        return self::getUserById($id)->sub_merchant;
    }

    public function findBabySittersIdsFromFavoritesOfParent(array $data, Parents $parent)
    {
        return $parent->favorite_baby_sitters()
            ->active()
            ->pricePerHour()
            ->childGenderStatus($data['child_gender_status'])
            ->acceptsDisabledChild($data['disabled_child'])
            ->gender($data['gender_id'])
            ->wcStatus($data['wc_status'] ? true : false)
            ->animalStatus($data['animal_status'] ? true : false)
            ->childrenCount($data['child_count'])
            ->shareableTalents($data['shareable_talents'])
            ->childYears($data['child_years'])
            //->depositPaid()
            ->availableTown($data['town_id'])
            ->dateTime($data['date'], $data['times'])->pluck('id');
    }

    public function findBabySitterForFilter(array $data)
    {
        $babySitters = BabySitter::acceptedLocation($data['location_id'])
            ->active()
            ->pricePerHour()
            ->childGenderStatus($data['child_gender_status'])
            ->acceptsDisabledChild($data['disabled_child'])
            ->gender($data['gender_id'])
            ->wcStatus($data['wc_status'] ? true : false)
            ->animalStatus($data['animal_status'] ? true : false)
            ->childrenCount($data['child_count'])
            ->shareableTalents($data['shareable_talents'])
            ->childYears($data['child_years'])
            //->depositPaid()
            ->availableTown($data['town_id'])
            ->dateTime($data['date'], $data['times']);

        if (isset($data['baby_sitter_id'])) {
            return $babySitters->where('id', $data['baby_sitter_id'])->first();
        }
        return $babySitters->get();
    }


}
