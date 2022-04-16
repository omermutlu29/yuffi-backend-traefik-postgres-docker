<?php

namespace App\Observers;

use App\Interfaces\NotificationInterfaces\INotification;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;

class AppointmentObserver
{
    private INotification $notificationService;

    public function __construct(INotification $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Appointment "created" event.
     *
     * @param \App\Models\Appointment $appointment
     * @return void
     * @throws \Exception
     */
    public function created(Appointment $appointment)
    {
        //TODO
        //1. appointment tarihine ait saatleri bakıcının ajandasında
        // rezerve olarak ata (1 saat öncesi ve sonrasını da blokla)
        //
        //2. ebeveyne bildirim gönder
        //Mesaj paneliniz aktif! Şimdi bakıcı ile doğrudan iletişime geçebilirsiniz. Eşleştiğiniz bakıcının
        //ilk 30 dakika iptal etme hakkı bulunmaktadır.
        //3. bakıyıca bildirim gider : Bir eşleşme gerçekleşti! Şimdi ebeveyn ile mesaj paneli üzerinden doğrudan iletişime
        //geçebilirsiniz! İlk 30 dakika iptal etme hakkınız bulunmaktadır!
        //
        //TODO
    }

    /**
     * Handle the Appointment "updated" event.
     *
     * @param \App\Models\Appointment $appointment
     * @return void
     */
    public function updated(Appointment $appointment)
    {
        if ($appointment->appointment_status_id == 2) {
            //TODO
            //bakıcı iptal ettiyse
            //TODO bakıcı iptal etti ve
        }
    }

    /**
     * Handle the Appointment "deleted" event.
     *
     * @param \App\Models\Appointment $appointment
     * @return void
     */
    public function deleted(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "restored" event.
     *
     * @param \App\Models\Appointment $appointment
     * @return void
     */
    public function restored(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     *
     * @param \App\Models\Appointment $appointment
     * @return void
     */
    public function forceDeleted(Appointment $appointment)
    {
        //
    }
}
