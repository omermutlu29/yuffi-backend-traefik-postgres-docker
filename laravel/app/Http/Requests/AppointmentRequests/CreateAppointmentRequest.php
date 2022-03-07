<?php

namespace App\Http\Requests\AppointmentRequests;

use App\Http\Requests\BaseApiRequest;
use Carbon\Carbon;

class CreateAppointmentRequest extends BaseApiRequest
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

            'create_params' => 'required',
            'create_params.baby_sitter_id'=>'required|exists:baby_sitters,id',
            'create_params.town_id' => 'required|exists:towns,id',
            'create_params.hour' => 'required|numeric|max:10|min:1',
            'create_params.location_id' => 'required|exists:appointment_locations,id',
            'create_params.location' => 'required',
            'create_params.children.*.gender_id' => 'required|exists:genders,id',
            'create_params.children.*.disable' => 'required|boolean',
            'create_params.children.*.child_year_id' => 'required|exists:child_years,id',
            'create_params.date' => 'required|date|date_format:d-m-Y|after_or_equal:' . $addThreeDays . '|before_or_equal:' . $add15Days,
            'create_params.time' => ['required', 'date_format:G:i', 'after_or_equal:' . $startTime, 'before_or_equal:' . $endTime],
            'create_params.gender_id' => 'required|exists:genders,id',
            'create_params.children' => 'required|array',
            'create_params.animal_status' => 'required|boolean',
            'create_params.wc_status' => 'required|boolean',
          //  'create_params.shareable_talents' => 'array',
          //  'create_params.shareable_talents.*' => 'exists:shareable_talents,id',
        ];
    }
}
