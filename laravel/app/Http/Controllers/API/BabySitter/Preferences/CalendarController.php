<?php


namespace App\Http\Controllers\API\BabySitter\Preferences;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AvailableTimeRequests\AvailableTimeDelete;
use App\Http\Requests\AvailableTimeRequests\AvailableTimeUpdate;
use App\Http\Requests\BabySitter\StoreAvailableTime;
use App\Http\Resources\CalendarGetResource;
use App\Interfaces\IServices\IBabySitterCalendarService;

class CalendarController extends BaseController
{
    private IBabySitterCalendarService $calendarService;

    public function __construct(IBabySitterCalendarService $calendarService)
    {
        $this->middleware(['auth:baby_sitter', 'bs_first_step', 'bs_second_step']);
        $this->calendarService = $calendarService;
    }

    public function index()
    {

        try {
            $data = [];
            foreach ($this->calendarService->getMyNextFifteenDaysCalendar(\auth()->id()) as $date) {
                $data[$date->date] = CalendarGetResource::prapareString($date);
            }
            return $this->sendResponse($data, 'Verileriniz getirildi');
        } catch (\Exception $exception) {
            return $this->sendError(false, $exception->getMessage(), 400);

        }
    }

    public function store(StoreAvailableTime $request)
    {
        try {
            return $this->sendResponse($this->calendarService->storeTime(\auth()->id(), $request->all()), 'Ajandanız başarılı bir şekilde  güncellendi');
        } catch (\Exception $exception) {
            return $this->sendError(false, $exception->getMessage(), 400);
        }
    }

    public function update(AvailableTimeUpdate $request)
    {
        try {
            return $this->sendResponse($this->calendarService->update(\auth()->id(), $request->only('available_time_id', 'time_status_id')), 'Güncelleme işlemi başarılı');
        } catch (\Exception $exception) {
            return $this->sendError(false, $exception->getMessage(), 400);

        }
    }

    public function delete(AvailableTimeDelete $request)
    {
        try {
            return $this->sendResponse($this->calendarService->delete(\auth()->id(), $request->only('available_time_id')), 'Silme işlemi başarılı');
        } catch (\Exception $exception) {
            return $this->sendError(false, $exception->getMessage(), 400);
        }
    }


}
