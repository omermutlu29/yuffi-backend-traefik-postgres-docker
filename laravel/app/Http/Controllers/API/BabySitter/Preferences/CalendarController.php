<?php


namespace App\Http\Controllers\API\BabySitter\Preferences;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AvailableTimeRequests\AvailableTimeDelete;
use App\Http\Requests\AvailableTimeRequests\AvailableTimeUpdate;
use App\Http\Requests\BabySitter\StoreAvailableTime;
use App\Interfaces\IServices\IBabySitterCalendarService;

class CalendarController extends BaseController
{
    private IBabySitterCalendarService $calendarService;

    public function __construct(IBabySitterCalendarService $calendarService)
    {
        $this->middleware([
                'auth:baby_sitter',
                'bs_first_step',
                //'bs_second_step'
            ]
        );
        $this->calendarService = $calendarService;
    }

    public function index()
    {
        try {
            $data = $this->calendarService->getMyNextFifteenDaysCalendar(auth()->id());
            return $this->sendResponse($data, 'Verileriniz getirildi');
        } catch (\Exception $exception) {
            return $this->sendError(false, $exception->getMessage(), 400);

        }
    }

    public function store(StoreAvailableTime $request)
    {
        try {
            return $this->sendResponse($this->calendarService->storeTime(\auth()->id(), $request->all()), 'Ajandaniz başarıyla güncellendi!');
        } catch (\Exception $exception) {
            return $this->sendError(false, $exception->getMessage(), 400);
        }
    }


    public function delete(AvailableTimeDelete $request)
    {
        try {
            return $this->sendResponse($this->calendarService->delete(\auth()->id(), (int)$request->get('available_time_id')), 'Silme işlemi başarılı');
        } catch (\Exception $exception) {
            return $this->sendError(false, $exception->getMessage(), 400);
        }
    }


}
