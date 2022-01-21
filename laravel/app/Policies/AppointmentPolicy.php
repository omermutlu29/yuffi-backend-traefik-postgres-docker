<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\BabySitter;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function update(BabySitter $babySitter, Appointment $appointment)
    {
        return $babySitter->id == $appointment->baby_sitter_id;
    }


}
