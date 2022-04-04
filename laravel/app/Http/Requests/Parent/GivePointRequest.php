<?php

namespace App\Http\Requests\Parent;

use App\Http\Requests\BaseApiRequest;
use App\Models\PointType;

class GivePointRequest extends BaseApiRequest
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
        $validationArray = [];
        $validationArray['points'] = 'required';
        foreach (PointType::all() as $pointType) {
            $validationArray['points.' . $pointType->id] = 'required';
            $validationArray['points.' . $pointType->id . '.point'] = 'required|min:0|max:5';
            $validationArray['points.' . $pointType->id . '.additional_text'] = 'max:100';
        }

        return $validationArray;
    }

    public function messages()
    {
        foreach (PointType::all() as $pointType) {
            $messages['points.' . $pointType->id] = $pointType->name . ' için puan gönderimi zorunludur';
        }
        return $messages;
    }
}
