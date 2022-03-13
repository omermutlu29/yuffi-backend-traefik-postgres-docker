<?php


namespace App\Http\Controllers\API\BabySitter\Appointment;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AppointmentRequests\ApproveDisapproveAppointmentRequest;
use App\Http\Requests\GetAppointmentDetailRequest;
use App\Http\Resources\AppointmentResource;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IServices\IAppointmentService;

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

    public function getAppointmentDetail(GetAppointmentDetailRequest $request)
    {
        $request->get('appointment_id');
        try {
            return $this->sendResponse(
                AppointmentResource::make($this->appointmentRepository->getAppointmentById($request->get('appointment_id'))),
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

    public function myApprovedAppointments()
    {
        return $this->appointmentService->pendingApproveAppointments(auth()->id());
    }

    public function myNotApprovedAppointments()
    {
        return $this->appointmentService->notApprovedAppointments(auth()->id());
    }

    public function myPaidAppointments()
    {
        return $this->appointmentService->paidAppointments(auth()->id());
    }

    public function myPendingPaymentAppointments()
    {
        return $this->appointmentService->pendingPayment(auth()->id());
    }

    public function approve(ApproveDisapproveAppointmentRequest $appointmentRequest)
    {
        try {
            $this->appointmentService->approveAppointment($appointmentRequest->appointment_id);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function disapprove(ApproveDisapproveAppointmentRequest $appointmentRequest)
    {
        try {
            return $this->appointmentService->disapproveAppointment($appointmentRequest->appointment_id);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
