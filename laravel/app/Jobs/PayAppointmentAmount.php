<?php

namespace App\Jobs;

use App\Interfaces\IServices\IAppointmentPayment;
use App\Interfaces\PaymentInterfaces\IPayment;
use App\Models\Appointment;
use JetBrains\PhpStorm\ArrayShape;

class PayAppointmentAmount implements IAppointmentPayment
{
    private IPayment $paymentService;
    private Appointment $appointment;

    public function __construct(IPayment $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function payToAppointment(Appointment $appointment, array $cardData)
    {

        $this->appointment = $appointment;
        $payment = $paymentStatus = $this->paymentService->pay(
            $cardData,
            $this->prepareProducts(),
            $this->prepareAddressInformation(),
            $this->prepareBuyerInformation(),
            $this->appointment->price,
            $this->appointment->id);

        if ($payment->getStatus() != "success") {
            throw new \Exception($payment->getErrorMessage(), 400);
        }

        if ($cardData['registerCard'] == true) {
            $appointment->parent->card_parents()->create([
                'carduserkey' => $payment->getCardUserKey(),
                'cardtoken' => $payment->getCardToken(),
                'cardalias' => $payment->getCardFamily(),
            ]);
        }

        $appointment->payment_transactions()->create([
            'payment_result' => $payment->getRawResult(),
            'is_success' => $payment->getStatus() == "success"
        ]);
        return $paymentStatus;
    }

    #[ArrayShape(['full_name' => "string", 'city' => "string", 'country' => "string", 'address' => "string", 'zip_code' => "string"])] private function prepareAddressInformation()
    {
        $parent = $this->appointment->parent;
        return [
            'full_name' => $parent->name . ' ' . $parent->surname,
            'city' => 'İstanbul',
            'country' => 'Türkiye',
            'address' => 'Hürriyet mahallesi sedef sokak 4/11',
            'zip_code' => '34520',
        ];
    }

    private function prepareProducts(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Bebek bakıcılığı',
                'category' => 'Bebek bakıcılığı',
                'price' => $this->appointment->price
            ]
        ];
    }

    private function prepareBuyerInformation(): array
    {
        $parent = $this->appointment->parent;
        return [
            'id' => $parent->id,
            'name' => $parent->name,
            'surname' => $parent->surname,
            'phone' => $parent->phone,
            'email' => $parent->email,
            'tc' => $parent->tc,
            'updated_at' => now()->format('Y-m-d H:i:s'),
            'created_at' => now()->format('Y-m-d H:i:s'),
            'ip' => \request()->ip(),
            'address' => 'Hürriyet mahallesi sedef sokak 3/11',
            'city' => 'İstanbul',
            'country' => 'Türkiye',
            'zip_code' => '34520',
        ];
    }


}
