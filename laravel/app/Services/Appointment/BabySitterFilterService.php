<?php

namespace App\Services\Appointment;

use App\Http\Resources\CalendarGetResource;
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

    public function findBabySitterForAppointment(array $data, Parents $parents)
    {
        try {
            $childYears = $this->getChildYearsAsArray($data['children']);
            $data['child_years'] = $childYears;
            unset($childYears);
            $childGenderStatus = $this->getChildGenderStatus($data['children']);
            $disabledChild = $this->areThereDisableChild($data['children']);
            $childCount = count($data['children']);
            $times = self::generateTimes($data['time'], $data['hour']);
            $data = self::prepareDataForQuery($childGenderStatus, $disabledChild, $childCount, $times, $data);
            unset($childGenderStatus, $disabledChild, $childCount, $times);
            $otherBabySitters = $this->babySitterRepository->findBabySitterForFilter($data);
            $favoritesIds = $this->babySitterRepository->findBabySittersIdsFromFavoritesOfParent($data, $parents);
            foreach ($otherBabySitters as $babySitter) {
                in_array($babySitter->id, $favoritesIds) ? $babySitter->is_favorite = 1 : $babySitter->is_favorite = 0;
            }
            $sorted = $otherBabySitters->sort('is_favorite', 'desc');
            unset($favoritesIds, $otherBabySitters);
            return $sorted->values()->all();;
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
            'animal_status' => $data['animal_status'],
            'wc_status' => $data['wc_status'],
            'shareable_talents' => isset($data['shareable_talents']) ? $data['shareable_talents'] : [],
            'child_years' => isset($data['child_years']) ? $data['child_years'] : [],
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
        return (CalendarGetResource::generateTimesForSearching($startTime, $hour));

    }

    private function getChildYearsAsArray(mixed $children)
    {
        $childYears = [];
        foreach ($children as $child) {
            $childYears[] = $child['child_year_id'];
        }
        return array_unique($childYears);

    }
}
