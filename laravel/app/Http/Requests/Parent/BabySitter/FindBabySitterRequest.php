<?php

namespace App\Http\Requests\Parent\BabySitter;

use App\Http\Requests\BaseApiRequest;
use Carbon\Carbon;

class FindBabySitterRequest extends BaseApiRequest
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
        $endTime = (Carbon::make('21:00')->format('H:i'));
        return [
            'search_param'=>'required',
            'search_param.town_id'=>'required|exists:towns,id',
            'search_param.date'=>'required|date|date_format:d-m-Y|after_or_equal:' . $addThreeDays . '|before_or_equal:' . $add15Days,
            'search_param.time'=>['required', 'date_format:G:i', 'after_or_equal:' . $startTime, 'before_or_equal:' . $endTime],
            'search_param.hour'=>'required|numeric|max:10|min:1',
            'search_param.gender_id'=>'required|exists:genders,id',
            'search_param.location_id'=>'required|exists:appointment_locations,id',
            'search_param.children'=>'required|array',
            'search_param.children.*.gender_id'=>'required|exists:genders,id',
            'search_param.children.*.disable'=>'required|boolean',
            'search_param.children.*.child_year_id'=>'required|exists:child_years,id',
            'search_param.animal_status'=>'required|boolean',
            'search_param.wc_status'=>'required|boolean',
        ];
    }


}
