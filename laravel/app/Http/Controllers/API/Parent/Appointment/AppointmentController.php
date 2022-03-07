<?php


namespace App\Http\Controllers\API\Parent\Appointment;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AppointmentRequests\CreateAppointmentRequest;
use App\Http\Requests\Parent\Appointment\ConfirmAppointmentAndPayRequest;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IServices\IAppointmentService;
use App\Models\Appointment;
use App\Services\Appointment\AppointmentPaymentService;
use App\Services\Appointment\BabySitterFilterService;
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

    public function confirmAppointmentPayThreeD(
        Appointment $appointment,
        ConfirmAppointmentAndPayRequest $request,
        AppointmentPaymentService $appointmentPaymentService): \Illuminate\Http\Response
    {
        try {
            $cardInformation = $request->only(['cardHolderName', 'cardNumber', 'expireMonth', 'expireYear', 'cvc']);
            $appointment = $this->appointmentRepository->getAppointmentById($request['appointment_id']);
            $appointmentPaymentService->payDirectly(auth()->user(), $appointment, $cardInformation);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }
    }

    public function completeAppointmentPayThreeD(Request $request, AppointmentPaymentService $appointmentPaymentService): \Illuminate\Http\Response
    {
        try {
            $data = $request->only('conversationId', 'paymentId', 'conversationData');
            $appointmentPaymentService->completeAppointmentPayment($data);
            return $this->sendResponse($this->appointmentRepository->getAppointmentById($request->conversationId), 'Payment Result');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }
    }

    public function createAppointment(CreateAppointmentRequest $request, IAppointmentService $appointmentService, BabySitterFilterService $appointmentFilterService)
    {
        $data = $request->only('create_params');
        $data = $data['create_params'];
        try {
            if ($appointmentFilterService->isBabySitterStillAvailable($data, $data['baby_sitter_id'])) {
                if(!$appointmentService->create($data['baby_sitter_id'], auth()->id(), $data)){
                    return $this->sendError('Hata!', 'Bir sorun oluştu lütfen tekrar deneyin!');
                }
                return $this->sendResponse(true,'Randevu başarı ile oluşturuldu',200);
            } else {
                return $this->sendError('Hata!', 'Bakıcı belirttiğiniz zaman(lar) içerisinde müsait görünmemektedir!');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getFile() . $e->getLine(), $e->getFile() . $e->getLine(), 400);
        }
    }
}
