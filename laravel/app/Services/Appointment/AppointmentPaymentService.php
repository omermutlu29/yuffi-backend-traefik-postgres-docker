<?php


namespace App\Services\Appointment;


use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
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


    public function __construct(
        IAppointmentRepository $appointmentRepository,
        IBabySitterRepository $babySitterRepository,
     )
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->babySitterRepository = $babySitterRepository;
    }

    public function payDirectly(Parents $parents, Appointment $appointment, array $cardInformation)
    {

    }





}
