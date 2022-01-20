<?php

namespace App\Http\Controllers\API\BabySitter\Preferences;

use App\Http\Controllers\API\BaseController;
use App\Models\City;
use App\Services\StaticVariables\StaticVariablesService;

class FillController extends BaseController
{
    private StaticVariablesService $variablesService;

    public function __construct(StaticVariablesService $variablesService)
    {
        $this->variablesService = $variablesService;
    }

    public function getGenders()
    {
        return $this->variablesService->getGenders();
    }

    public function getChildGenders()
    {
        return $this->variablesService->getChildGenders();
    }

    public function getChildYears()
    {
        return $this->variablesService->getChildYears();
    }

    public function getTowns(City $city)
    {
        return $this->variablesService->getTowns($city);
    }

    public function getLocations()
    {
        return $this->variablesService->getLocations();
    }

    public function getAll(City $city)
    {
        return $this->variablesService->getAll($city);
    }

    public function getNextDays()
    {
        $days = $this->variablesService->getNextDays();
        return $this->sendResponse($days, null);
    }

    private function calculateDays()
    {
        return $this->variablesService->calculateDays();
    }

    private function calculateTimes()
    {
        return $this->variablesService->calculateTimes();
    }

    private function hours()
    {
        return $this->variablesService->hours();
    }
}
