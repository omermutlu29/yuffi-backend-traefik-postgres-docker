<?php


namespace App\Services\Appointment;


use App\Http\Resources\ParentPaymentResource;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\PaymentInterfaces\IPayToSubMerchantService;
use App\Models\Appointment;
use App\Models\Parents;

class AppointmentPaymentService
{
    private IAppointmentRepository $appointmentRepository;
    private IBabySitterRepository $babySitterRepository;
    private IPayToSubMerchantService $payToSubMerchant;

    public function __construct(IAppointmentRepository $appointmentRepository, IBabySitterRepository $babySitterRepository, IPayToSubMerchantService $payToSubMerchantService)
    {
        $this->payToSubMerchant = $payToSubMerchantService;
        $this->appointmentRepository = $appointmentRepository;
        $this->babySitterRepository = $babySitterRepository;
    }

    public function payDirectly(Parents $parents, Appointment $appointment, array $cardInformation)
    {
        $babySitterSubMerchantKey = $this->babySitterRepository->getSubMerchantId($appointment->baby_sitter_id);
        $buyer = new ParentPaymentResource($parents);
        $buyer = $buyer->toArray($parents);
        $product = [['id' => $appointment->id, 'name' => 'Bakıcı Hizmeti', 'category' => 'Bakım Hizmeti', 'price' => $appointment->price]];
        $address = ['contact_name' => auth()->user()->name . ' ' . auth()->user()->surname, 'city' => 'İstanbul', 'country' => 'Türkiye', 'address' => auth()->user()->address, 'zip_code' => 34520];
        $this->payToSubMerchant->payToSubMerchant($cardInformation, $product, $address, $buyer, $appointment->price, 'TRY', 1, $appointment->id, $babySitterSubMerchantKey, ($appointment->price - 5));
    }


    public function payThreeD(){

    }
}
