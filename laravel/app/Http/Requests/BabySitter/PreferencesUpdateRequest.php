<?php

namespace App\Http\Requests\BabySitter;

use App\Http\Requests\BaseApiRequest;

class PreferencesUpdateRequest extends BaseApiRequest
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
            'child_year_id'=>'required|exists:genders,id',
            'child_count'=>'required|min:1',
            'disabled_status'=>'required',
            'animal_status'=>'required',
            'towns'=>'required|array|min:1',
            'towns.*'=>'required|exists:towns,id',
            'accepted_locations'=>'required|array|min:1',
            'accepted_locations.*'=>'required|exists:appointment_locations,id',


        ];
    }


}
