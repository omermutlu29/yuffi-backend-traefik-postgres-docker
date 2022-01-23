<?php

namespace App\Policies;

use App\Models\ParentChild;
use App\Models\Parents;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChildPolicy
{
    use HandlesAuthorization;

    private static function parentControl(Parents $parent, ParentChild $parentChild): bool
    {
        return $parentChild->parent_id == $parent->id;
    }

    public function update(Parents $parent, ParentChild $parentChild)
    {
        return self::parentControl($parent, $parentChild);
    }


    public function delete(Parents $parent, ParentChild $parentChild)
    {
        return self::parentControl($parent, $parentChild);
    }


    public function restore(Parents $parent, ParentChild $parentChild)
    {
        return self::parentControl($parent, $parentChild);
    }


}
