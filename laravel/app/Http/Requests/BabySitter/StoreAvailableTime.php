<?php

namespace App\Http\Requests\BabySitter;

use App\Http\Requests\BaseApiRequest;
use Carbon\Carbon;

class StoreAvailableTime extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $addThreeDays = today()->addDays(3)->format('d-m-Y');
        $add15Days = today()->addDays(15)->format('d-m-Y');
        $startTime = (Carbon::make('10:00')->format('H:i'));
        $endTime = (Carbon::make('22:00')->format('H:i'));
        return [
            'available_dates' => 'required|array',
            'available_dates.*.date' => 'required|date|date_format:d-m-Y|after_or_equal:' . $addThreeDays . '|before_or_equal:' . $add15Days,
            'available_dates.*.hours' => 'required|array',
            'available_dates.*.hours.*.start' => ['required', 'date_format:G:i', 'after_or_equal:' . $startTime, 'before_or_equal:' . $endTime],
            'available_dates.*.hours.*.end' => ['required', 'date_format:G:i', 'after:available_dates.*.hours.*.start', 'after_or_equal:' . $startTime, 'before_or_equal:' . $endTime]
        ];
    }


}
