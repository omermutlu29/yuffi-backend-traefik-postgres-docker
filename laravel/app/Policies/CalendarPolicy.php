<?php

namespace App\Policies;

use App\Models\BabySitter;
use App\Models\BabySitterAvailableTime;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarPolicy
{
    use HandlesAuthorization;

    public function update(BabySitter $babySitter, BabySitterAvailableTime $babySitterAvailableTime)
    {
        return $babySitterAvailableTime->baby_sitter_available_date->baby_sitter_id == $babySitter->id;
    }

    public function delete(BabySitter $babySitter, BabySitterAvailableTime $babySitterAvailableTime)
    {
        return $babySitterAvailableTime->baby_sitter_available_date->baby_sitter_id == $babySitter->id;
    }

}
