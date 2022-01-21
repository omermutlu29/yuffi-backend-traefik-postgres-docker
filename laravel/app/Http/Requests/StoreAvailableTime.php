<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }
}
