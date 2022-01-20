<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvailableTime extends FormRequest
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
        $addThreeDays = today()->addDays(3);
        return [
            'available_dates'=>'required|array',
            'available_dates.*.date'=>'required|date|date_format:d/m/Y|after:'.$addThreeDays,
            'available_dates.*.hours'=>'required|array',
            'available_dates.*.hours.*.start'=>['required','regex:/(\d+\:\d+)/',]

        ];
    }
}
