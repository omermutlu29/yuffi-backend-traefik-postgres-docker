<?php


namespace App\Http\Controllers\API\BabySitter\Message;

use App\Models\Appointment;
use App\Models\AppointmentMessage;
use App\Http\Controllers\API\BabySitter\Message\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MessageResource;


class MessageController extends NotificationController
{
    public function __construct()
    {
        $this->middleware(['auth:baby_sitter', 'bs_first_step', 'bs_second_step', 'deposit']);
    }

    public function sendMessage(Appointment $appointment, Request $request)
    {
        $baby_sitter = Auth::user();
        if ($appointment->baby_sitter->id = $baby_sitter->id) {
            $to = $appointment->parent->google_st;
            $message=new AppointmentMessage();
            $message->phone=$baby_sitter->phone;
            $message->user_type='App\BabySitter';
            $message->message=$request->message;
            $message->send_status=1;
            $message->saw=0;
            $result = $appointment->appointment_messages()->save($message);
            if ($result) {
                $result=$this->push('Yeni Mesaj', $request->message, $to);
                $data['message_send']=$result;
                $data['other_messages']=$this->getMessages($appointment);
                return $this->sendResponse($data,'Veriler Getirildi!');
            } else {
                return $this->sendError('Mesaj Gönderilemedi!');
            }
        }
    }

    public function getMessages(Appointment $appointment)
    {
        $result=$appointment->appointment_messages()->where('phone','!=',Auth::user()->phone)->update(['saw'=>1]);
        //dd($appointment->appointment_messages);
        return MessageResource::collection($appointment->appointment_messages);
    }

}
