<?php


namespace App\Services\StaticVariables;


use App\Interfaces\IServices\IStaticVars;
use App\Models\AppointmentLocation;
use App\Models\ChildYear;
use App\Models\City;
use App\Models\Gender;
use App\Models\ShareableTalent;

class StaticVariablesService implements IStaticVars
{
    public function getGenders()
    {
        return Gender::all();
    }

    public function getChildGenders()
    {
        return Gender::where('name', '!=', 'Fark etmez')->get();
    }

    public function getChildYears()
    {
        return ChildYear::orderBy('id','ASC')->get();
    }

    public function getTowns(City $city)
    {
        return $city->towns()->where('is_active', true)->get();
    }

    public function getLocations()
    {
        return AppointmentLocation::all();
    }

    public function getAll(City $city)
    {

        $data = [];
        $data['towns'] = $this->getTowns($city);
        $data['locations'] = $this->getLocations();
        $data['child_years'] = $this->getChildYears();
        $data['child_genders'] = $this->getChildGenders();
        $data['genders'] = $this->getGenders();
        $data['days'] = $this->calculateDays();
        $data['times'] = $this->calculateTimes();
        $data['hours'] = $this->hours();
        $data['child_count'] = $this->getChildCount();
        $data['talents'] = $this->getTalents();
        return $data;
    }

    public function getNextDays()
    {
        $days = $this->getNextDays();
        return $this->sendResponse($days, null);
    }

    public function calculateDays()
    {
        $days = [];
        $date = date('Y-m-d');
        $obj = new \stdClass();
        $obj->name = $date;
        $obj->value = 'Bugün';
        $days[] = $obj;
        for ($i = 1; $i <= 14; $i++) {

            if ($i == 1) {
                $date = date('d-m-Y', strtotime('+1 day', strtotime($date)));
                $obj = new \stdClass();
                $obj->name = $date;
                $obj->value = 'Yarın';
                $days[] = $obj;
            } else {
                $date = date('d-m-Y', strtotime('+1 day', strtotime($date)));
                $obj = new \stdClass();
                $obj->name = $date;
                $obj->value = $date;
                $days[] = $obj;
            }

        }
        return $days;

    }

    public function calculateTimes()
    {
        $times = [];
        for ($i = 10; $i < 22; $i++) {
            $times[] = $i . ':' . '00';
        }
        return $times;
    }

    public function hours()
    {
        $times = [];
        for ($i = 1; $i < 4; $i++) {
            $times[] = $i;
        }
        return $times;
    }

    public function getChildCount()
    {
        $arr = [];
        for ($i = 1; $i <= 3; $i++) {
            $arr[] = ['label' => (string)$i, 'value' => (string)$i];
        }
        return $arr;
    }

    public function getTalents()
    {
        return ShareableTalent::all();
    }
}
