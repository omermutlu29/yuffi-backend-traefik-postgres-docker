<?php


namespace App\Http\Controllers\API\BabySitter\Preferences;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AvailableTimeDelete;
use App\Http\Requests\AvailableTimeUpdate;
use App\Http\Requests\StoreAvailableTime;
use App\Interfaces\IServices\IBabySitterCalendarService;

class CalendarController extends BaseController
{
    private IBabySitterCalendarService $calendarService;

    public function __construct(IBabySitterCalendarService $calendarService)
    {
        $this->middleware(['auth:baby_sitter', 'bs_first_step', 'bs_second_step', 'deposit']);
        $this->calendarService = $calendarService;
    }

    public function index()
    {
        try {
            return $this->calendarService->getMyNextFifteenDaysCalendar(\auth()->id());
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public function store(StoreAvailableTime $request): \Illuminate\Http\Response
    {
        try {
           return $this->calendarService->storeTime(\auth()->id(), $request->all());
        } catch (\Exception $exception) {
            throw $exception;
        }
        return $this->sendResponse($this->get(), 'Kaydetme işlemi başarılı!');
    }

    public function update(AvailableTimeUpdate $request)
    {
        try {
            $this->calendarService->update(\auth()->id(),$request->only('available_time_id','time_status_id'));
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public function delete(AvailableTimeDelete $request)
    {
        try {
            return $this->calendarService->delete(\auth()->id(),$request->only('available_time_id'));
        }catch (\Exception $exception){
            throw $exception;
        }
    }


}
