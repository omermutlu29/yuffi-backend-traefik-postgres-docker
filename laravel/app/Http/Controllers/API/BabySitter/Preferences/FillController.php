<?php

namespace App\Http\Controllers\API\BabySitter\Preferences;

use App\Http\Controllers\API\BaseController;
use App\Models\City;
use App\Services\StaticVariables\StaticVariablesService;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $days = $this->variablesService->calculateDays();
        return $this->sendResponse($days, null);
    }

    public function getTimes(Request $request)
    {
        try {
            if (Carbon::createFromFormat('d-m-Y', $request->get('date')) > Carbon::today()->format('d-m-Y')) {
                throw new \Exception('Hata', 'Gönderdiğiniz tarihin bugünden büyük veya eşit olması gerekiyor');
            }
            $date = Carbon::createFromFormat('d-m-Y', $request->get('date'))->format('d-m-Y');
            $today = Carbon::today()->format('d-m-Y');

            if ($date == $today) {
                $startTime = ceil((float)Carbon::now()->addHours(3)->format('H.i'));
                return $this->variablesService->calculateTimes($startTime);
            } else {
                return $this->variablesService->calculateTimes(10);
            }
        } catch (\Exception $exception) {
            return $this->sendError('Hata', 'hata',400);
        }


    }

    public function getHours(Request $request)
    {

        return $this->variablesService->hours();
    }

    public function getChildCount()
    {
        return $this->variablesService->getChildCount();
    }

    public function getTalents()
    {
        return $this->variablesService->getTalents();
    }


}
