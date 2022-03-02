<?php


namespace App\Interfaces\IRepositories;


use App\Models\BabySitter;

interface IBabySitterRepository
{
    public function updateAcceptedLocations(BabySitter $babySitter, array $acceptedLocations);

    public function updateAvailableTowns(BabySitter $babySitter, array $availableTowns);

    public function updateShareableTalents(BabySitter $babySitter, array $shareableTalents);

    public function getSubMerchantId($id);

    /**
     * @param array $data
     * $data[
     * 'location_id','child_gender_status','disabled_child','gender_id','child_count','town_id','date','times'
     * ]
     * @return mixed
     */
    public function findBabySitterForFilter(array $data);

}
