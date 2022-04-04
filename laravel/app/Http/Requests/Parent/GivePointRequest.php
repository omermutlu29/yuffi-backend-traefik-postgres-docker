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
        $validationArray = [
            'appointment_points' => 'required',
        ];
        foreach (PointType::all() as $item) {
            $validationArray['appointment_points.' . $item->id . '.point'] = 'required|number|max:5|min:0';
        }
        return $validationArray;
    }
}
