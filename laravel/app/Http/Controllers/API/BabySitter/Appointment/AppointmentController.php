<?php


namespace App\Http\Controllers\API\BabySitter\Appointment;


use App\Models\Appointment;
use App\Http\Controllers\API\BaseController;
use App\Services\NotificationServices\PushNotificationService;

class AppointmentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:baby_sitter');
    }

    public function index(){
        $data = [
            'pending_approve' => \auth()->user()->appointments()->pendingApprove()->get(),
            'pending_payment' => \auth()->user()->appointments()->pendingPayment()->get(),
            'paid' => \auth()->user()->appointments()->paid()->get(),
            'not_approved' => \auth()->user()->appointments()->notApproved()->get(),
        ];
        return $this->sendResponse($data, 'Randevular Getirildi');
    }

    public function approve(Appointment $appointment){
        $appointment->baby_sitter_approved=1;
        $appointment->appointment_status_id=3;
        $appointment->save();
        (new PushNotificationService())->push('Kabul etti','şöyle oldu böyle oldu',$appointment->parent->google_st);
    }

    public function disapprove(Appointment $appointment){
        $appointment->baby_sitter_approved=0;
        $appointment->appointment_status_id=5;
        $appointment->save();
        (new PushNotificationService())->push('Kabul etmedi','şöyle oldu böyle oldu',$appointment->parent->google_st);

    }
}
