<?php

namespace App\Jobs;

use App\Interfaces\NotificationInterfaces\INotification;
use App\Interfaces\PaymentInterfaces\IPaymentWithRegisteredCard;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PayAppointmentAmount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Appointment $appointment;
    private string $cardUserKey;
    private string $cardToken;
    private array $addressInformation;
    private array $products;
    private array $buyerInformation;
    private IPaymentWithRegisteredCard $paymentService;
    private INotification $notificationService;

    /**
     * Create a new job instance.
     *
     * @param Appointment $appointment
     * @throws \Exception
     */
    public function __construct(IPaymentWithRegisteredCard $paymentWithRegisteredCardService, Appointment $appointment, INotification $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->paymentService = $paymentWithRegisteredCardService;
        $this->appointment = $appointment;
        $this->prepareCardData();
        $this->prepareAddressInformation();
        $this->prepareProducts();
        $this->prepareBuyerInformation();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {

        $result = $this->paymentService->payWithRegisteredCardForVirtualProducts(
            $this->cardToken,
            $this->cardUserKey,
            $this->products,
            $this->addressInformation,
            $this->buyerInformation,
            $this->appointment->price,
            1);
        $result = json_decode($result->getRawResult());
        if ($result->status == "success") {
            $this->notificationService->notify(
                ['appointment_id' => $this->appointment->id, 'type' => 'appointment_list'],
                'Ödemeniz başarı ile gerçekleşti',
                'Ödemeniz başarı ile gerçekleşti. Bakıcı ile iletişime geçebilirsiniz!',
                $this->appointment->parent->google_st);
        }
        if ($result->status !== "success") {
            $this->notificationService->notify(
                ['appointment_id' => $this->appointment->id, 'type' => 'credit_cards'],
                'Ödemeniz alınamadı',
                'Randevu için ödemeniz alınamadı lütfen kredi kartınızın limitini kontrol edin!',
                $this->appointment->parent->google_st);
        }
    }

    private function prepareCardData()
    {
        $cardInformation = $this->appointment->parent->card_parents;
        if (count($cardInformation) != 1) {
            throw new \Exception('Kayıtlı kart yok veya birden fazla', 400);
        }
        $this->cardUserKey = $cardInformation[0]->carduserkey;
        $this->cardToken = $cardInformation[0]->cardtoken;
    }

    private function prepareAddressInformation()
    {
        $parent = $this->appointment->parent;
        $this->addressInformation = [
            'full_name' => $parent->name . ' ' . $parent->surname,
            'city' => 'İstanbul',
            'country' => 'Türkiye',
            'address' => $parent->address,
            'zip_code' => '34520',
        ];
    }

    private function prepareProducts()
    {
        $this->products = [
            [
                'id' => 1,
                'name' => 'Bebek bakıcılığı',
                'category' => 'Bebek bakıcılığı',
                'price' => $this->appointment->price
            ]
        ];
    }

    private function prepareBuyerInformation()
    {
        $parent = $this->appointment->parent;
        $this->buyerInformation = [
            'id' => $parent->id,
            'name' => $parent->name,
            'surname' => $parent->surname,
            'phone' => $parent->phone,
            'email' => $parent->email,
            'tc' => $parent->tc,
            'updated_at' => now()->format('Y-m-d H:i:s'),
            'created_at' => now()->format('Y-m-d H:i:s'),
            'ip' => \request()->ip(),
            'address' => $parent->address,
            'city' => 'İstanbul',
            'country' => 'Türkiye',
            'zip_code' => '34520',
        ];
    }
}
