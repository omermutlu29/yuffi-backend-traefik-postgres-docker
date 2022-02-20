<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\IBabySitterCalendarRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Models\BabySitterAvailableDate;
use App\Models\BabySitterAvailableTime;

class CalendarRepository implements IBabySitterCalendarRepository
{
    private IBabySitterRepository $babySitterRepository;

    public function __construct(IBabySitterRepository $babySitterRepository)
    {
        $this->babySitterRepository = $babySitterRepository;
    }

    public function getMyNextFifteenDays($babySitterId)
    {
        try {
            return $this->babySitterRepository->getUserById($babySitterId)->baby_sitter_available_dates()->nextFifteenDays()->get();
        } catch (\Illuminate\Database\QueryException $exception) {
            return $exception;
        }
    }

    public function deleteAvailableTime($availableTimeId)
    {
        try {
            BabySitterAvailableTime::find($availableTimeId)->delete();
        } catch (\Illuminate\Database\QueryException $exception) {
            throw $exception;
        }
    }

    public function updateAvailableTime($availableTimeId, $status = null)
    {
        try {
            BabySitterAvailableTime::find($availableTimeId)->update(['status' => $status]);
        } catch (\Illuminate\Database\QueryException $exception) {
            throw $exception;
        }
    }

    public function storeDate($babySitterId, $date)
    {
        try {
            return BabySitterAvailableDate::firstOrCreate(['date' => $date, 'baby_sitter_id' => $babySitterId]);
        } catch (\Illuminate\Database\QueryException $exception) {
            throw $exception;
        }
    }

    public function storeTime(BabySitterAvailableDate $babySitterAvailableDate, $start, $finish, int $timeStatus): \Illuminate\Database\Eloquent\Model
    {
        try {
            return $babySitterAvailableDate->times()->firstOrCreate([
                'start' => $start,
                'finish' => $finish,
                'time_status_id' => $timeStatus
            ]);
        } catch (\Illuminate\Database\QueryException $exception) {
            throw $exception;
        }
    }

    public function getAvailableTimeByIdWithDate(int $availableTimeId)
    {
        try {
            BabySitterAvailableTime::with('baby_sitter_available_date')->find($availableTimeId);
        } catch (\Illuminate\Database\QueryException $exception) {
            throw $exception;
        }
    }
}
