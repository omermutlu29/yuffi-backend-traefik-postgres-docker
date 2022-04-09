<?php


namespace App\Http\Controllers\API\Parent\Appointment;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AppointmentRequests\CreateAppointmentRequest;
use App\Http\Requests\GetAppointmentDetailRequest;
use App\Http\Requests\Parent\Appointment\AppointmentCancelRequest;
use App\Http\Resources\AppointmentResource;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IServices\IAppointmentPayment;
use App\Interfaces\IServices\IAppointmentService;
use App\Interfaces\PaymentInterfaces\IPaymentWithRegisteredCard;
use App\Models\Appointment;
use App\Services\Appointment\BabySitterFilterService;

class AppointmentController extends BaseController
{
    private IAppointmentRepository $appointmentRepository;

    public function __construct(IAppointmentRepository $appointmentRepository)
    {
        $this->middleware('auth:parent');
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAppointmentDetail(GetAppointmentDetailRequest $request, Appointment $appointment)
    {
        try {
            return $this->sendResponse(
                AppointmentResource::make($appointment),
                'Randevularınız getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getMessage(), 400);
        }
    }

    public function getPastAppointments()
    {
        try {
            return $this->sendResponse(
                AppointmentResource::collection($this->appointmentRepository->getPastAppointmentsByParentId(auth()->id())),
                'Randevularınız getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getMessage(), 400);
        }

    }

    public function getFutureAppointments()
    {
        try {
            return $this->sendResponse(
                AppointmentResource::collection($this->appointmentRepository->getFutureAppointmentsByParentId(auth()->id())), 'Randevularınız getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getMessage(), 400);
        }
    }

    public function createAppointment(
        CreateAppointmentRequest $request,
        IAppointmentService $appointmentService,
        BabySitterFilterService $appointmentFilterService,
        IAppointmentPayment $paymentService
    )
    {
        //try {
            $data = $request->manipulateData();
            $cardData = $request->generateCardData($data);
            //Bakıcı halen müsait mi ?

            $appointmentFilterService->isBabySitterStillAvailable($data, $data['baby_sitter_id']) ??
            throw new \Exception('Bakıcı belirttiğiniz zaman(lar) içerisinde müsait görünmemektedir!', 400);

            //Appointment oluşturulabildi mi ?
            $appointment = $appointmentService->create($data['baby_sitter_id'], auth()->id(), $data) ??
                throw new \Exception('Bir sorun oluştu lütfen tekrar deneyin!', 400);

            //Ödeme alınabildi mi ?
            $paymentService->payToAppointment($appointment, $cardData);
            return $this->sendResponse(true, 'Randevu başarı ile oluşturuldu', 200);
        //} catch (\Exception $exception) {
        //    return $this->sendError('Hata', ['message' => $exception->getMessage()], 400);
       // }
    }

    public function cancelAppointment(AppointmentCancelRequest $request, IAppointmentService $appointmentService)
    {
        //try {
            if ($appointmentService->cancelAppointment((int)$request->appointment_id, auth()->user())) {
                return $this->sendResponse(true, 'Randevu başarı ile iptal edildi');
            } else {
                return $this->sendError('Hata', ['hata' => 'randevu iptal edilirken bir hata ile karşılaşıldı'], 400);
            }

        /*} catch (\Exception $exception) {
            return $this->sendError('Hata', null, 400);
        }*/
    }


}
