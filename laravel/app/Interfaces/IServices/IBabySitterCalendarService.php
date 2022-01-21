<?php


namespace App\Interfaces\IServices;



interface IBabySitterCalendarService
{
    public function storeTime($babySitterId, array $data);
    public function getMyNextFifteenDaysCalendar($babySitterId);
    public function delete(int $babySitterId,int $availableTimeId);
    public function update(int $babySitterId,int $availableTimeId,int $status);
}
