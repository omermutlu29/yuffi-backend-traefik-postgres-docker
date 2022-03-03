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
            'price_per_hour' => 'required|numeric|max:500|min:75',
            'child_gender_id' => 'required|exists:genders,id',
            'parent_gender_id' => 'required|exists:genders,id',
            'child_count' => 'required|min:1',
            'disabled_status' => 'required',
            'wc_status' => 'required',
            'animal_status' => 'required',
            'towns' => 'required|array|min:1',
            'towns.*' => 'required|exists:towns,id',
            'accepted_locations' => 'required|array|min:1',
            'accepted_locations.*' => 'required|exists:appointment_locations,id',
            'shareable_talents' => 'array',
            'shareable_talents.*' => 'numeric|exists:shareable_talents,id',
            'child_years' => 'required|array|min:1',
            'child_years.*' => 'required|numeric|exists:child_years,id',
        ];
    }


}
