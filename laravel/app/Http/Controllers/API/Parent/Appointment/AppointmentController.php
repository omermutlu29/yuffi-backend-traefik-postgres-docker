<?php


namespace App\Http\Controllers\API\Parent\Appointment;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\ConfirmAppointmentAndPayRequest;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Services\Appointment\AppointmentPaymentService;

class AppointmentController extends BaseController
{

    /**
     * @var IAppointmentRepository
     */
    private IAppointmentRepository $appointmentRepository;

    public function __construct(IAppointmentRepository $appointmentRepository)
    {
        $this->middleware('auth:parent');
        $this->appointmentRepository = $appointmentRepository;
    }

    public function confirmAppointmentAndPay(ConfirmAppointmentAndPayRequest $request, AppointmentPaymentService $appointmentPaymentService)
    {
        $cardInformation = $request->only(['cardHolderName', 'cardNumber', 'expireMonth', 'expireYear', 'cvc']);
        $appointment = $this->appointmentRepository->getAppointmentById($request['appointment_id']);
        $appointmentPaymentService->payDirectly($appointment, $cardInformation);

    }


}
