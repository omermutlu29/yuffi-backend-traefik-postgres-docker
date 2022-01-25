<?php
namespace App\Services\Appointment;

use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Models\Parents;
use JetBrains\PhpStorm\ArrayShape;

class BabySitterFilterService
{
    private IBabySitterRepository $babySitterRepository;

    public function __construct(IBabySitterRepository $babySitterRepository)
    {
        $this->babySitterRepository = $babySitterRepository;
    }

    public function findBabySitterForAppointment(Parents $parents, array $data)
    {
        try {
            $childGenderStatus = self::getChildGenderStatus($parents);
            $disabledChild = self::areThereDisableChild($parents);
            $childCount = $parents->parent_children()->count();
            $times = self::generateTimes($data['time'], $data['hour']);
            $data = self::prepareDataForQuery($childGenderStatus,$disabledChild,$childCount,$times,$data);
            unset($childGenderStatus, $disabledChild, $childCount, $times);
            return $this->babySitterRepository->findBabySitterForFilter($data);
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public function isBabySitterStillAvailable(Parents $parents, array $data,int $babySitterId){
        try {
            $childGenderStatus = self::getChildGenderStatus($parents);
            $disabledChild = self::areThereDisableChild($parents);
            $childCount = $parents->parent_children()->count();
            $times = self::generateTimes($data['time'], $data['hour']);
            $data = self::prepareDataForQuery($childGenderStatus,$disabledChild,$childCount,$times,$data);
            $data['baby_sitter_id']=$babySitterId;
            unset($childGenderStatus, $disabledChild, $childCount, $times);
            return $this->babySitterRepository->findBabySitterForFilter($data);
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    #[ArrayShape(['location_id' => "mixed", 'child_gender_status' => "", 'disabled_child' => "", 'gender_id' => "mixed", 'child_count' => "", 'town_id' => "mixed", 'date' => "mixed", 'times' => ""])]
    private static function prepareDataForQuery($childGenderStatus, $disabledChild, $childCount, $times, array $data): array
    {
        return [
            'location_id' => $data['location_id'],
            'child_gender_status' => $childGenderStatus,
            'disabled_child' => $disabledChild,
            'gender_id' => $data['gender_id'],
            'child_count' => $childCount,
            'town_id' => $data['town_id'],
            'date' => $data['date'],
            'times' => $times
        ];
    }

    private static function getChildGenderStatus($parent): int
    {
        $child_gender_male = false;
        $child_gender_female = false;

        foreach ($parent->parent_children as $child) {
            if ($child->gender_id == 1) {
                $child_gender_male = true;
            } else {
                $child_gender_female = true;
            }
        }
        if ($child_gender_female && $child_gender_male) {
            return 3;
        } elseif ($child_gender_male && !$child_gender_female) {
            return 1;
        } elseif ($child_gender_female && !$child_gender_male) {
            return 2;
        }
    }

    private function areThereDisableChild($parent): bool
    {
        foreach ($parent->parent_children as $child) {
            if ($child->disable == 1) {
                return true;
            }
        }
        return false;
    }

    private function generateTimes($startTime, $hour): array
    {
        $times = [];
        for ($i = 0; $i < $hour; $i++) {
            $time = (date('H:i', strtotime("+" . $i . " Hour " . $startTime)));
            $times[] = $time;
        }
        return $times;
    }
}
