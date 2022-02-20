<?php


namespace App\Services\Calendar;


use App\Interfaces\IRepositories\IBabySitterCalendarRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IBabySitterCalendarService;
use Carbon\Carbon;

class BabySitterCalendarService implements IBabySitterCalendarService
{
    private IBabySitterCalendarRepository $babySitterCalendarRepository;

    public function __construct(IBabySitterCalendarRepository $babySitterCalendarRepository)
    {
        $this->babySitterCalendarRepository = $babySitterCalendarRepository;
    }

    public function storeTime($babySitterId, array $data): void
    {
        try {
            foreach ($data['available_dates'] as $date) {
                $babySitterAvailableDate = $this->babySitterCalendarRepository->storeDate($babySitterId,Carbon::make($date['date'])->format('m-d-Y'));
                foreach ($date['hours'] as $hour) {
                    $this->babySitterCalendarRepository->storeTime($babySitterAvailableDate, $hour['start'], $hour['end'], 1);
                }
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getMyNextFifteenDaysCalendar($babySitterId)
    {
        try {
            return $this->babySitterCalendarRepository->getMyNextFifteenDays($babySitterId);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function delete(int $babySitterId, int $availableTimeId)
    {
        $timeWithDate = $this->babySitterCalendarRepository->getAvailableTimeByIdWithDate($availableTimeId);
        if (\request()->user()->can('delete', $timeWithDate)) {
            abort(403);
        }
        try {
            return $this->babySitterCalendarRepository->deleteAvailableTime($availableTimeId);
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
