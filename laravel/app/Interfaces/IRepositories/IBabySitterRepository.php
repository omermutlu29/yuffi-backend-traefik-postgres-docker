<?php


namespace App\Interfaces\IRepositories;


use App\Models\BabySitter;

interface IBabySitterRepository
{
    public function updateAcceptedLocations(BabySitter $babySitter, array $acceptedLocations);
    public function updateAvailableTowns(BabySitter $babySitter, array $availableTowns);

}
