<?php


namespace App\Http\Controllers\API\Parent\Message;


use App\Models\Appointment;
use App\Models\AppointmentMessage;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\Parent\Message\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MessageResource;

class MessageController extends NotificationController
{

    public function __construct()
    {
        $this->middleware(['auth:parent']);
    }

    public function sendMessage(Appointment $appointment, Request $request)
    {
        $parent = Auth::user();
        if ($appointment->parent->id = $parent->id) {
            $to = $appointment->baby_sitter->google_st;
            $message=new AppointmentMessage();
            $message->phone=$parent->phone;
            $message->user_type='App\Parents';
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
        return MessageResource::collection($appointment->appointment_messages);
    }
}
