<?php


namespace App\Http\Controllers\API\BabySitter\Appointment;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AppointmentRequests\ApproveDisapproveAppointmentRequest;
use App\Http\Requests\GetAppointmentDetailRequest;
use App\Http\Resources\AppointmentResource;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IServices\IAppointmentService;
use App\Models\Appointment;
use App\Services\Appointment\AppointmentService;

class AppointmentController extends BaseController
{
    private IAppointmentService $appointmentService;
    private IAppointmentRepository $appointmentRepository;


    public function __construct(IAppointmentService $appointmentService, IAppointmentRepository $appointmentRepository)
    {
        $this->middleware('auth:baby_sitter');
        $this->appointmentService = $appointmentService;
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAppointmentDetail(GetAppointmentDetailRequest $request, Appointment $appointment)
    {
        try {
            return $this->sendResponse(AppointmentResource::make($appointment),
                'Randevularınız getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getMessage(), 400);
        }
    }

    public function getPastAppointments()
    {
        try {
            return $this->sendResponse(
                AppointmentResource::collection($this->appointmentRepository->getPastAppointmentsByBabySitterId(auth()->id())),
                'Randevularınız getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getMessage(), 400);
        }

    }

    public function getFutureAppointments()
    {
        try {
            return $this->sendResponse(
                AppointmentResource::collection($this->appointmentRepository->getFutureAppointmentsByBabySitterId(auth()->id())), 'Randevularınız getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getMessage(), 400);
        }
    }

    public function getCanceledAppointments()
    {
        try {
            return $this->sendResponse(
                AppointmentResource::collection($this->appointmentRepository->getFutureAppointmentsByBabySitterId(auth()->id())), 'Randevularınız getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getMessage(), 400);
        }
    }

    public function getUpcomingAppointments()
    {
        try {
            return $this->sendResponse(
                AppointmentResource::collection($this->appointmentRepository->getUpcomingAppointments(auth()->id())), 'Randevularınız getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError('Hata', $exception->getMessage(), 400);
        }
    }


    public function disapprove(ApproveDisapproveAppointmentRequest $appointmentRequest, AppointmentService $appointmentService)
    {
        try {
            if ($appointmentService->cancelAppointment((int)$appointmentRequest->get('appointment_id'), auth()->user())) {
                return $this->sendResponse(true, 'Randevu başarı ile iptal edildi');
            } else {
                return $this->sendError('Hata', ['hata' => 'Randevu iptal edilirken bir hata ile karşılaşıldı'], 400);
            }

        } catch (\Exception $exception) {
            return $this->sendError('Hata', null, 400);
        }
    }
}
