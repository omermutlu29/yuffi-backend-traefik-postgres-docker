<?php


namespace App\Http\Controllers\API\Parent\Appointment;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\ConfirmAppointmentAndPayRequest;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Services\Appointment\AppointmentPaymentService;
use Illuminate\Http\Request;

class AppointmentController extends BaseController
{
    private IAppointmentRepository $appointmentRepository;

    public function __construct(IAppointmentRepository $appointmentRepository)
    {
        $this->middleware('auth:parent');
        $this->appointmentRepository = $appointmentRepository;
    }

    public function confirmAppointmentAndPay(ConfirmAppointmentAndPayRequest $request, AppointmentPaymentService $appointmentPaymentService): \Illuminate\Http\Response
    {
        try {
            $cardInformation = $request->only(['cardHolderName', 'cardNumber', 'expireMonth', 'expireYear', 'cvc']);
            $appointment = $this->appointmentRepository->getAppointmentById($request['appointment_id']);
            $appointmentPaymentService->payDirectly(auth()->user(), $appointment, $cardInformation);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }

    }

    public function confirmAppointmentPayThreeD(ConfirmAppointmentAndPayRequest $request, AppointmentPaymentService $appointmentPaymentService): \Illuminate\Http\Response
    {
        try {
            $cardInformation = $request->only(['cardHolderName', 'cardNumber', 'expireMonth', 'expireYear', 'cvc']);
            $appointment = $this->appointmentRepository->getAppointmentById($request['appointment_id']);
            $appointmentPaymentService->payDirectly(auth()->user(), $appointment, $cardInformation);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }
    }

    public function completeAppointmentPayThreeD(Request $request,AppointmentPaymentService $appointmentPaymentService): \Illuminate\Http\Response
    {
        try {
            $data = $request->only('conversationId', 'paymentId', 'conversationData');
            $appointmentPaymentService->completeAppointmentPayment($data);
            return $this->sendResponse($this->appointmentRepository->getAppointmentById($request->conversationId),'Payment Result');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }
    }


}
