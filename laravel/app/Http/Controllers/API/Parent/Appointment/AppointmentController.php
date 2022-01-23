<?php


namespace App\Http\Controllers\API\Parent\Appointment;


use App\Models\Appointment;
use App\Http\Controllers\API\BaseController;
use App\Services\IyzicoService;
use Illuminate\Http\Request;

class AppointmentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:parent');
    }



    public function payToAppointment(Appointment $appointment, Request $request)
    {
        //TODO
        $card = new \stdClass();
        $card->cardHolderName = $request->cardHolderName;
        $card->cardNumber = $request->cardNumber;
        $card->expireMonth = $request->expireMonth;
        $card->expireYear = $request->expireYear;
        $card->cvc = $request->cvc;
        $card->register = $request->has('register') ? 1 : 0;


        $buyer = new \stdClass();
        $buyer->name = \auth()->user()->name;
        $buyer->surname = \auth()->user()->surname;
        $buyer->phone = \auth()->user()->phone;
        $buyer->email = \auth()->user()->email;
        $buyer->tc = \auth()->user()->tc;
        $buyer->last_login = \auth()->user()->updated_at;
        $buyer->address = $appointment->town->name.' '.$appointment->town->city->name.'/Türkiye';
        $buyer->country = 'Türkiye';
        $buyer->city = $appointment->town->city->name;

        $address=new \stdClass();
        $address->name_surname=auth()->user()->name.' '.auth()->user()->surname;
        $address->address=$appointment->town->name.' '.$appointment->town->city->name.'/Türkiye';
        $address->city=$appointment->town->city->name;
        $address->country='Türkiye';
        $paymentService = new IyzicoService($appointment->id, $appointment->price, $card,$buyer,$address,null);
        $result=$paymentService->payToMerchant($appointment->baby_sitter->sub_merchant);
        return $result->getPaymentStatus();

        /**
         * result ı kontrol et
         * eğer başarılı ise

        $appointment->appointment_status_id=4;
        $appointment->save();
        (new PushNotificationService())->push('Kabul etti','şöyle oldu böyle oldu',$appointment->baby_sitter->google_st);
         */
        /**
         * değilse

        $appointment->appointment_status_id=6;
        $appointment->save();
        (new PushNotificationService())->push('Kabul etti','şöyle oldu böyle oldu',$appointment->baby_sitter->google_st);
         */

    }


}
