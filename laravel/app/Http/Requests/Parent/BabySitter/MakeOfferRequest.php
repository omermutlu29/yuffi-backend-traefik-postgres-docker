<?php

namespace App\Http\Requests\Parent\BabySitter;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class MakeOfferRequest extends FormRequest
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
            'search_param.location_id'=>'required|exists:locations,id',
            'baby_sitter_id'=>'required|exists:baby_sitter,id'
        ];
    }
}
