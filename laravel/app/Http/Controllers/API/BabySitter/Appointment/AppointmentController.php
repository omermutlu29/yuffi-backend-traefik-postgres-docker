<?php


namespace App\Http\Controllers\API\BabySitter\Appointment;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AppointmentRequests\ApproveDisapproveAppointmentRequest;
use App\Interfaces\IServices\IAppointmentService;

class AppointmentController extends BaseController
{
    private IAppointmentService $appointmentService;

    public function __construct(IAppointmentService $appointmentService)
    {
        $this->middleware('auth:baby_sitter');
        $this->appointmentService = $appointmentService;
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
