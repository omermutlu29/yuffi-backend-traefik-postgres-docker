<?php

namespace App\Http\Requests\Parent\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentCancelRequest extends FormRequest
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
            'appointment_id' => 'required|exists:appointments,id'
        ];
    }
}
