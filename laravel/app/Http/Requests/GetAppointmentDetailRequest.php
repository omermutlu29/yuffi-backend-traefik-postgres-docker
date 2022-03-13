<?php

namespace App\Http\Requests;

use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Models\BabySitter;
use App\Models\Parents;

class GetAppointmentDetailRequest extends BaseApiRequest
{
    private IAppointmentRepository $appointmentRepository;

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
        $appointment = $this->appointmentRepository->getAppointmentById(\request()->get('appointment_id'));
        if (\request()->user() instanceof BabySitter) {
            return $appointment->baby_sitter_id === \request()->user()->id;
        }

        if (\request()->user() instanceof Parents) {
            return $appointment->parent_id === \request()->user()->id;
        }

        return false;
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