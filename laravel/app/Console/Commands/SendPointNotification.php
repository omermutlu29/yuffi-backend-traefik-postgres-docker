<?php

namespace App\Console\Commands;

use App\Interfaces\NotificationInterfaces\INotification;
use App\Models\Appointment;
use Illuminate\Console\Command;

class SendPointNotification extends Command
{
    private INotification $notification;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification-point:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(INotification $notification)
    {
        $this->notification = $notification;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $appointments = Appointment::notificationDidNotSent()->with('parent')->get();
        foreach ($appointments as $appointment) {
            $this
                ->notification
                ->notify(
                    ['appointment_id' => $appointment->id, 'type' => 'past_appointment_list'],
                    'Puanlama Aktif!', 'Dilerseniz aldığınız hizmeti değerlendirebilirsiniz.',
                    $appointment->parent->google_st);
        }

    }
}
