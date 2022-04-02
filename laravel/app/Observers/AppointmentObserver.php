<?php

namespace App\Observers;

use App\Interfaces\NotificationInterfaces\INotification;
use App\Models\Appointment;

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
        if ($appointment->baby_sitter->google_st !== null) {
            $this->notificationService->notify(['appointment_id' => $appointment->id, 'type' => 'appointment_list'], 'Yeni Randevu!', 'Yeni bir randevu oluştu, 30 dakika içerisinde iptal etmezsen kabul etmiş sayılacaksın', $appointment->baby_sitter->google_st);
        }

        if ($appointment->parent->google_st) {
            $this->notificationService->notify(['appointment_id' => $appointment->id, 'type' => 'appointment_list'], 'Yeni Randevu!', 'Yeni randevu oluştu, bakıcı 30 dakika içerisinde iptal etmezse kartınızdan ödeme alınacaktır!', $appointment->parent->google_st);
        }
        $startDate = $appointment->date;
        $startDate = \Carbon\Carbon::create($startDate . ' ' . $appointment->start);
        $range = (now()->diffInHours($startDate));
        $paymentJob = new \App\Jobs\PayAppointmentAmount($appointment);
        if ($range > 48) {
            $startTime = now()->addHours($range - 48);
            $paymentJob->delay($startTime);
        }


        $jobId = app(\Illuminate\Contracts\Bus\Dispatcher::class)->dispatch($paymentJob);
        $appointment->job_id = $jobId;
        $appointment->save();

    }

    /**
     * Handle the Appointment "updated" event.
     *
     * @param \App\Models\Appointment $appointment
     * @return void
     */
    public function updated(Appointment $appointment)
    {
        if ($appointment->appointment_status_id == 5) {
            //TODO
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
