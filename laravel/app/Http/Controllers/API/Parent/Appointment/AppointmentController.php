<?php


namespace App\Http\Controllers\API\Parent\Appointment;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Parent\Appointment\ConfirmAppointmentAndPayRequest;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Models\Appointment;
use App\Models\BabySitter;
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

    public function createAppointment(Request $request, BabySitter $babySitter, BabySitterFilterService $appointmentFilterService): \Illuminate\Http\Response
    {
        $data = $request->only('search_param');
        try {
            if ($appointmentFilterService->isBabySitterStillAvailable(\auth()->user(), $data, $babySitter->id)) {
                return $this->sendResponse($this->appointmentRepository->create([
                    'baby_sitter_id' => $babySitter->id,
                    'parent_id' => auth()->id(),
                    'hour' => $data['hour'],
                    'price' => $data['hour'] * $babySitter->price_per_hour,
                    'date' => $data['date'],
                    'start' => $data['time'],
                    'finish' => (date('H:i', strtotime("+" . $data['hour'] . " Hour " . $data['time']))),
                    'appointment_location_id' => $data['location_id'],
                    'location' => $data['location'],
                    'town_id' => $data['town_id'],
                    'appointment_status_id' => 1
                ]), 'Randevu yaratıldı');
            } else {
                return $this->sendError('Hata!', 'Bakıcı belirttiğiniz zaman(lar) içerisinde müsait görünmemektedir!');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null, $e->getCode());
        }
    }
}
