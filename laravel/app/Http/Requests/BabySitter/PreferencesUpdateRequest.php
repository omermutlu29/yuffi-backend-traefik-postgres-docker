<?php

namespace App\Http\Requests\BabySitter;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PreferencesUpdateRequest extends FormRequest
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
        return [
            'price_per_hour'=>'required',
            'child_gender_id'=>'required|exists:genders,id',
            'child_year_id'=>'required|exists:child_genders,id',
            'child_count'=>'required|min:1',
            'disabled_status'=>'required',
            'animal_status'=>'required',
            'towns'=>'required|array|min:1',
            'towns.*'=>'required|exists:towns,id',
            'accepted_locations'=>'required|array|min:1',
            'accepted_locations.*'=>'required|exists:appointment_locations,id',


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