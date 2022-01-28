<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\BabySitter;
use App\Models\Parents;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\App;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function update(BabySitter|Parents $user, Appointment $appointment)
    {
        if ($user instanceof Parents) {
            return $appointment->parent_id === $user->id;
        }
        return $user->id == $appointment->baby_sitter_id;
    }

    public function confirmAppointmentAndPay(Parents $parents, Appointment $appointment)
    {
        return ($appointment->parent_id == $parents->id && $appointment->baby_sitter_approved == true);
    }

    public function canSendMessage(Parents|BabySitter $user, Appointment $appointment){
        if ($user instanceof Parents) {
            return $appointment->parent_id === $user->id;
        }
        return $user->id == $appointment->baby_sitter_id;
    }


}
