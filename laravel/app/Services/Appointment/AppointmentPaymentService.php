<?php


namespace App\Services\Appointment;


use App\Http\Resources\ParentPaymentResource;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\PaymentInterfaces\ICompleteThreeDPayment;
use App\Interfaces\PaymentInterfaces\IPaymentToSubMerchant;
use App\Interfaces\PaymentInterfaces\IThreeDPaymentToSubMerchant;
use App\Models\Appointment;
use App\Models\Parents;

class AppointmentPaymentService
{
    const CURRENCY = 'TRY';
    const INSTALLMENT = 1;
    const DEPOSIT = 30;
    const MD_STATUSES = [
        '0' => '3-D Secure imzası geçersiz veya doğrulama',
        '2' => 'Kart sahibi veya bankası sisteme kayıtlı değil',
        '3' => 'Kartın bankası sisteme kayıtlı değil',
        '4' => 'Doğrulama denemesi, kart sahibi sisteme daha sonra kayıt olmayı seçmiş',
        '5' => 'Doğrulama yapılamıyor',
        '6' => '3-D Secure hatası',
        '7' => 'Sistem hatası',
        '8' => 'Bilinmeyen kart no',
    ];

    private IAppointmentRepository $appointmentRepository;
    private IBabySitterRepository $babySitterRepository;
    private IThreeDPaymentToSubMerchant $payToSubMerchantThreeD;
    private IPaymentToSubMerchant $payToSubMerchant;
    private ICompleteThreeDPayment $completeThreeDPaymentService;

    public function __construct(
        IAppointmentRepository $appointmentRepository,
        IBabySitterRepository $babySitterRepository,
        IThreeDPaymentToSubMerchant $payToSubMerchantThreeD,
        IPaymentToSubMerchant $payToSubMerchantService,
        ICompleteThreeDPayment $completeThreeDPaymentService
    )
    {
        $this->payToSubMerchantThreeD = $payToSubMerchantThreeD;
        $this->appointmentRepository = $appointmentRepository;
        $this->babySitterRepository = $babySitterRepository;
        $this->payToSubMerchant = $payToSubMerchantService;
        $this->completeThreeDPaymentService = $completeThreeDPaymentService;
    }

    public function payDirectly(Parents $parents, Appointment $appointment, array $cardInformation)
    {
        try {
            $babySitterSubMerchantKey = $appointment->baby_sitter->sub_merchant;
            $buyer = new ParentPaymentResource($parents);
            $buyer = $buyer->toArray($parents);
            $product = [['id' => $appointment->id, 'name' => 'Bakıcı Hizmeti', 'category' => 'Bakım Hizmeti', 'price' => $appointment->price]];
            $address = ['contact_name' => $parents->name . ' ' . $parents->surname, 'city' => 'İstanbul', 'country' => 'Türkiye', 'address' => $parents->address, 'zip_code' => 34520];
            $paymentResult = $this->payToSubMerchant->payToSubMerchant(
                $cardInformation,
                $product,
                $address,
                $buyer,
                $appointment->price,
                'TRY',
                1,
                $appointment->id,
                $babySitterSubMerchantKey,
                ($appointment->price - 5)
            );
            $this->appointmentRepository->updateAppointment($appointment->id, $paymentResult->getRawResult());
            return $this->appointmentRepository->getAppointmentById($appointment->id);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function payThreeD(Parents $parents, Appointment $appointment, array $cardInformation)
    {
        try {
            $babySitterSubMerchantKey = $this->babySitterRepository->getSubMerchantId($appointment->baby_sitter_id);
            $buyer = new ParentPaymentResource($parents);
            $buyer = $buyer->toArray($parents);
            $product = [['id' => $appointment->id, 'name' => 'Bakıcı Hizmeti', 'category' => 'Bakım Hizmeti', 'price' => $appointment->price]];
            $address = ['contact_name' => $parents->name . ' ' . $parents->surname, 'city' => 'İstanbul', 'country' => 'Türkiye', 'address' => $parents->address, 'zip_code' => 34520];
            return $this->payToSubMerchantThreeD->initializeThreeDForSubMerchant(
                $cardInformation,
                $product,
                $address,
                $buyer,
                $appointment->price,
                'TRY',
                1,
                $appointment->id,
                $babySitterSubMerchantKey,
                ($appointment->price - 5),
                route('appointment.pay.complete'));
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function completeAppointmentPayment(array $data)
    {
        try {
            if (isset(self::MD_STATUSES[$data['mdStatus']])) {
                return ['status' => $data['status'], 'errorCode' => $data['mdStatus'], 'errorMessage' => self::MD_STATUSES[$data['mdStatus']]];
            }
            $paymentResult = $this->completeThreeDPaymentService->completeThreeDPayment($data['conversationId'], $data['paymentId'], $data['conversationData']);
            $this->appointmentRepository->updateAppointment($data['conversationId'], $paymentResult->getRawResult());
            return $this->appointmentRepository->getAppointmentById($data['conversationId']);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
