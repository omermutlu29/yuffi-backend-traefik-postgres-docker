<?php


namespace App\Interfaces\IRepositories;


use App\Models\BabySitterAvailableDate;

interface IBabySitterCalendarRepository
{
    public function getMyNextFifteenDays($babySitterId);

    public function storeDate($babySitterId, $date);

    public function storeTime(BabySitterAvailableDate $babySitterAvailableDate, $start, $finish, int $timeStatus);

    public function deleteAvailableTime($availableTimeId);

    public function updateAvailableTime($availableTimeId, $status);

    public function getAvailableTimeByIdWithDate(int $availableTimeId);
}
