<?php

namespace App\Services\Appointment;

use App\Interfaces\IRepositories\IBabySitterRepository;
use JetBrains\PhpStorm\ArrayShape;

class BabySitterFilterService
{
    private IBabySitterRepository $babySitterRepository;

    public function __construct(IBabySitterRepository $babySitterRepository)
    {
        $this->babySitterRepository = $babySitterRepository;
    }

    public function findBabySitterForAppointment(array $data)
    {
        try {
            $childGenderStatus = $this->getChildGenderStatus($data['children']);
            $disabledChild = $this->areThereDisableChild($data['children']);
            $childCount = count($data['children']);

            $times = self::generateTimes($data['time'], $data['hour']);
            $data = self::prepareDataForQuery($childGenderStatus, $disabledChild, $childCount, $times, $data);
            unset($childGenderStatus, $disabledChild, $childCount, $times);
            return $this->babySitterRepository->findBabySitterForFilter($data);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function isBabySitterStillAvailable(array $data, int $babySitterId)
    {
        try {
            $childGenderStatus = $this->getChildGenderStatus($data['children']);
            $disabledChild = $this->areThereDisableChild($data['children']);
            $childCount = count($data['children']);

            $times = $this->generateTimes($data['time'], $data['hour']);
            $data = $this->prepareDataForQuery($childGenderStatus, $disabledChild, $childCount, $times, $data);
            $data['baby_sitter_id'] = $babySitterId;
            unset($childGenderStatus, $disabledChild, $childCount, $times);
            return $this->babySitterRepository->findBabySitterForFilter($data);
        } catch (\Exception $exception) {
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
            'times' => $times,
            'animal_status'=>$data['animal_status'],
            'wc_status'=>$data['wc_status']
        ];
    }

    private static function getChildGenderStatus($children): int
    {
        $child_gender_male = false;
        $child_gender_female = false;

        foreach ($children as $child) {
            if ($child['gender_id'] == 1) {
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

    private function areThereDisableChild(array $children): bool
    {
        foreach ($children as $child) {
            if ($child['disable']) {
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
