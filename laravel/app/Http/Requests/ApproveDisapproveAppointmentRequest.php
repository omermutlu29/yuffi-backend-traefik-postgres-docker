<?php

namespace App\Http\Requests;

use App\Interfaces\IRepositories\IAppointmentRepository;
use Illuminate\Foundation\Http\FormRequest;

class ApproveDisapproveAppointmentRequest extends FormRequest
{
    private $appointmentRepository;

    public function __construct(IAppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->appointmentRepository->getAppointmentById(\request()->post('appointment_id'));
        return \request()->user()->can('update', $appointment);
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
