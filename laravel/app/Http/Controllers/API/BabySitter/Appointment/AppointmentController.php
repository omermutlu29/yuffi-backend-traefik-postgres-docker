<?php


namespace App\Http\Controllers\API\BabySitter\Appointment;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AppointmentRequests\ApproveDisapproveAppointmentRequest;
use App\Http\Requests\GetAppointmentDetailRequest;
use App\Http\Resources\AppointmentResource;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IServices\IAppointmentService;
use App\Models\Appointment;

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


    public function disapprove(ApproveDisapproveAppointmentRequest $appointmentRequest)
    {
        try {
            return $this->sendResponse($this->appointmentService->disapproveAppointment($appointmentRequest->appointment_id),'Başarılı bir şekilde iptal edildi!');
        } catch (\Exception $exception) {
            $this->sendError('Hata', $exception->getMessage(), 400);
        }
    }
}
