<?php

namespace App\Providers;

use App\Events\NewAppointmentMessageEvent;
use App\Listeners\NewAppointmentMessageListener;
use App\Models\Appointment;
use App\Models\AppointmentMessage;
use App\Observers\AppointmentMessageObserver;
use App\Observers\AppointmentObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        NewAppointmentMessageEvent::class => [
            NewAppointmentMessageListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        AppointmentMessage::observe(AppointmentMessageObserver::class);
        Appointment::observe(AppointmentObserver::class);
    }
}
