<?php

namespace App\Http\Requests\AppointmentRequests;

use App\Http\Requests\BaseApiRequest;
use App\Interfaces\IRepositories\IAppointmentRepository;

class ApproveDisapproveAppointmentRequest extends BaseApiRequest
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
