<?php

namespace App\Http\Requests\Parent;

use App\Http\Requests\BaseApiRequest;

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
        return [
            'appointment_points' => 'required',
            'appointment_points.*.appointment_id' => 'required|exists:appointments,id',
            'appointment_points.*.point_type' => 'required|exists:point_types,id',
            'appointment_points.*.point' => 'required|min:1|max:5|numeric',
            'appointment_points.*.additional_text' => 'max:600'
        ];
    }
}
