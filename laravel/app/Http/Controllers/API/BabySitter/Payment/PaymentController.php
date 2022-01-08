<?php


namespace App\Http\Controllers\API\BabySitter\Payment;


use App\Models\Appointment;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Auth;

class PaymentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:baby_sitter');
    }

    public function index()
    {
        $baby_sitter = Auth::user();
        $appointments= $baby_sitter->appointments()->where('appointment_status_id',3)->with(['appointment_status','parent'])->get();
        if (count($appointments)>0){
            return $this->sendResponse($appointments,'Randevular getirildi!');
        }else{
            return $this->sendError('Hata','Henüz kayıtlı randevunuz bulunmamaktadır!');
        }
    }

    public function all(){
        $baby_sitter = Auth::user();
        $appointments= $baby_sitter->appointments()->with(['appointment_status','parent'])->get();
        if (count($appointments)>0){
            return $this->sendResponse($appointments,'Randevular getirildi!');
        }else{
            return $this->sendError('Hata','Henüz kayıtlı randevunuz bulunmamaktadır!');
        }
    }

}
