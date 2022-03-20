<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarGetResource extends JsonResource
{
    public function toJson($options = 1)
    {
        return parent::toJson($options); // TODO: Change the autogenerated stub
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];
        $data[$this->date] = $this->prapareString($this);
        return $data;
    }

    public static function prapareString($date)
    {
        $existTimes = [];
        $nonExistsTimes = self::fillTimesToNonExistDate($date->date);

        foreach ($date->times as $time) {
            $string['id'] = $time->id;
            $string['date'] = $date->date;
            $string['starts'] = \Carbon\Carbon::createFromFormat('H:i:s', $time->start)->format('H:i');
            $string['end'] = \Carbon\Carbon::createFromFormat('H:i:s', $time->finish)->format('H:i');
            $string['name'] = $string['starts'] . ' - ' . $string['end']; //. ' ve ' . $time->time_status->name;
            $string['status_id'] = $time->time_status_id;
            $existTimes[] = $string;


        }

        return collect(array_merge($nonExistsTimes, $existTimes))->sortBy('starts')->values();


    }

    public static function fillTimesToNonExistDate($date)
    {
        $minutes = ['00', '30'];
        $data = [];
        for ($i = 10; $i < 22; $i++) {

            foreach ($minutes as $minute) {
                $string['date'] = $date;
                $string['starts'] = $i . ':' . $minute;
                $string['end'] = $minute == '00' ? $i . ':' . '30' : $i + 1 . ':' . '00';
                $string['name'] = $string['starts'] . ' - ' . $string['end'];
                $string['status_id'] = 999;//Eklenmemiş
                $data[] = $string;
            }
        }
        return $data;
    }

    public static function generateTimesForSearching($start, $hour)
    {
        $times = [];
        $loop = $hour * 2;
        $start = Carbon::createFromFormat('H:i', $start);
        for ($i = 0; $i < $loop; $i++) {
            $start = $start->addMinutes(30);
            $times[] = $start->format('H:i');
        }
        return $times;

    }
}
