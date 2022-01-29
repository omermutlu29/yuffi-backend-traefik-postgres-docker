<?php

namespace App\Http\Requests;

use App\Interfaces\IRepositories\IAppointmentRepository;
use Illuminate\Contracts\Validation\Validator;

class ConfirmAppointmentAndPayRequest extends CreditCardRequest
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
        $appointment = $this->appointmentRepository->getAppointmentById(request()->post('appointment_id'));
        return request()->user()->can('confirmAppointmentAndPay', $appointment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['appointment_id'] = 'required|exists:appointments,id';
        return $rules;
    }

    public function messages()
    {
        return parent::messages(); // TODO: Change the autogenerated stub
    }

    protected function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator); // TODO: Change the autogenerated stub
    }
}