<?php

namespace App\Observers;

use App\Interfaces\NotificationInterfaces\INotification;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentObserver
{
    private INotification $notificationService;

    public function __construct(INotification $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function created(Appointment $appointment)
    {
        try {
            $this->updateTimesOfBabySitter($appointment, 3);
            $this->notificationService->notify(['appointment_id' => $appointment->id, 'type' => 'messaging'], 'Yeni Mesaj', 'Mesaj paneliniz aktif! Şimdi bakıcı ile doğrudan iletişime geçebilirsiniz. Eşleştiğiniz bakıcının ilk 30 dakika iptal etme hakkı bulunmaktadır.', $appointment->parent->google_st);
            $this->notificationService->notify(['appointment_id' => $appointment->id, 'type' => 'messaging'], 'Yeni Mesaj', 'Bir eşleşme gerçekleşti! Şimdi ebeveyn ile mesaj paneli üzerinden doğrudan iletişime geçebilirsiniz. İlk 30 dakika iptal etme hakkınız bulunmaktadır!', $appointment->baby_sitter->google_st);
        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\Log::info($exception);
        }
    }

    public function updated(Appointment $appointment)
    {
        if ($appointment->appointment_status_id == 2) {
            $this->notificationService->notify(['appointment_id' => $appointment->id, 'type' => 'messaging'], 'Yeni Mesaj', 'Bakıcınız buluşmayı iptal etti. Dilerseniz şimdi yeni bir arama yapabilirsiniz. Ücret iadesi hesabınıza yansıtılacaktır.', $appointment->parent->google_st);
            $this->updateTimesOfBabySitter($appointment, 1);

        }
        if ($appointment->appointment_status_id == 3) {
            $message = 'Eşleşme ebeveyn tarafından iptal edildi.';
            $timeRange = $appointment->rejected_time_range;
            if ($timeRange < 12) {
                $message = $message . " Belirlemiş olduğunuz bakıcılık bedelinin 1/3’ü tarafınıza iade edilecektir.";
            }
            $message = $message . " Buluşma saati tekrar ajandanızda açık hale getirilmiştir.";
            $this->notificationService->notify(['appointment_id' => $appointment->id, 'type' => 'messaging'], 'Yeni Mesaj', $message, $appointment->baby_sitter->google_st);
            $this->updateTimesOfBabySitter($appointment, 1);
        }
    }

    private function updateTimesOfBabySitter(Appointment $appointment, $timeStatus)
    {
        $babySitter = $appointment->baby_sitter;
        $date = $babySitter->baby_sitter_available_dates()->where('date', $appointment->date)->first();
        if ($date) {
            $startTime = Carbon::createFromFormat('H:i:s', $appointment->start);
            $finishTime = Carbon::createFromFormat('H:i:s', $appointment->finish);
            $startTime->subHours(1);
            $finishTime->addHours(1);
            $date->times()->whereTime('start', '>=', $startTime)
                ->whereTime('finish', '<=', $finishTime)
                ->update(['time_status_id' => $timeStatus]);
        }
    }


}
