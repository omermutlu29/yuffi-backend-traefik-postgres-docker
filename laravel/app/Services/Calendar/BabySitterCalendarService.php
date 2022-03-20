<?php


namespace App\Services\Calendar;


use App\Http\Resources\CalendarGetResource;
use App\Interfaces\IRepositories\IBabySitterCalendarRepository;
use App\Interfaces\IServices\IBabySitterCalendarService;
use Carbon\Carbon;

class BabySitterCalendarService implements IBabySitterCalendarService
{
    private IBabySitterCalendarRepository $babySitterCalendarRepository;

    public function __construct(IBabySitterCalendarRepository $babySitterCalendarRepository)
    {
        $this->babySitterCalendarRepository = $babySitterCalendarRepository;
    }

    public function storeTime($babySitterId, array $data)
    {
        try {
            foreach ($data['available_dates'] as $date) {
                $babySitterAvailableDate = $this->babySitterCalendarRepository->storeDate($babySitterId, Carbon::make($date['date'])->format('m-d-Y'));
                foreach ($date['hours'] as $hour) {
                    return $this->babySitterCalendarRepository->storeTime($babySitterAvailableDate, $hour['start'], $hour['end'], 1);
                }
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getMyNextFifteenDaysCalendar($babySitterId)
    {
        try {
            $dbData = $this->babySitterCalendarRepository->getMyNextFifteenDays($babySitterId);
            $data = [];
            $processableDates = [];
            $startDate = Carbon::now();
            $finishDate = Carbon::now()->addDays(15);

            foreach ($dbData as $date) {
                $data[$date->date] = CalendarGetResource::prapareString($date);
            }
            for ($i = $startDate; $i < $finishDate; $i->addDays(1)) {
                if (!isset($date[$i->format('Y-m-d')])) {
                    $processableDates[$i->format('Y-m-d')] = CalendarGetResource::fillTimesToNonExistDate($i->format('Y-m-d'));
                }
            }
            $data = array_merge($processableDates, $data);
            return $data;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function delete(int $babySitterId, int $availableTimeId)
    {
        try {
            return ($this->babySitterCalendarRepository->deleteAvailableTime($availableTimeId));
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function update(int $babySitterId, int $availableTimeId, int $status)
    {
        $timeWithDate = $this->babySitterCalendarRepository->getAvailableTimeByIdWithDate($availableTimeId);
        if (\request()->user()->can('update', $timeWithDate)) {
            abort(403);
        }
        try {
            return $this->babySitterCalendarRepository->updateAvailableTime($availableTimeId, $status);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }


}
