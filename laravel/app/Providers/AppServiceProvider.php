<?php

namespace App\Providers;

use App\Console\Commands\DeactivateBabySitters;
use App\Http\Controllers\API\BabySitter\Auth\LoginController as BabySitterLoginController;
use App\Http\Controllers\API\BabySitter\Auth\ProfileController;
use App\Http\Controllers\API\BabySitter\Auth\ProfileController as BabySitterProfileController;
use App\Http\Controllers\API\Parent\Auth\LoginController as ParentLoginController;
use App\Http\Controllers\API\Parent\Auth\ProfileController as ParentProfileController;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterCalendarRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\ICardRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IAppointmentService;
use App\Interfaces\IServices\IBabySitterCalendarService;
use App\Interfaces\IServices\IChangableActiveStatus;
use App\Interfaces\IServices\ILoginService;
use App\Interfaces\IServices\IMessagingService;
use App\Interfaces\IServices\IPointService;
use App\Interfaces\IServices\IProfileService;
use App\Interfaces\NotificationInterfaces\INotification;
use App\Interfaces\PaymentInterfaces\IPaymentWithRegisteredCard;
use App\Interfaces\PaymentInterfaces\IRegisterCardService;
use App\Jobs\PayAppointmentAmount;
use App\Listeners\NewAppointmentMessageListener;
use App\Observers\AppointmentObserver;
use App\Repositories\AppointmentRepository;
use App\Repositories\BabySitterRepository;
use App\Repositories\CalendarRepository;
use App\Repositories\CardRepository;
use App\Repositories\ParentRepository;
use App\Services\Appointment\AppointmentService;
use App\Services\Calendar\BabySitterCalendarService;
use App\Services\LoginService\LoginService;
use App\Services\Messaging\MessagingService;
use App\Services\NotificationServices\NetGSMSmsNotification;
use App\Services\NotificationServices\PushNotificationService;
use App\Services\PaymentServices\Iyzico\IyzicoPaymentService;
use App\Services\PaymentServices\Iyzico\IyzicoRegisterCardService;
use App\Services\PointService\PointService;
use App\Services\ProfileService\BabySitterProfileService;
use App\Services\ProfileService\ParentProfileService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
        $this->app->when(PayAppointmentAmount::class)->needs(INotification::class)->give(PushNotificationService::class);
        $this->app->when(AppointmentObserver::class)->needs(INotification::class)->give(PushNotificationService::class);
        $this->app->when(NewAppointmentMessageListener::class)->needs(INotification::class)->give(PushNotificationService::class);
        $this->app->bind(IMessagingService::class, MessagingService::class);
        $this->app->when(CardRepository::class)->needs(IUserRepository::class)->give(ParentRepository::class);

        $this->app->bind(ICardRepository::class, CardRepository::class);
        $this->app->bind(IRegisterCardService::class, IyzicoRegisterCardService::class);

        //Payment Systems
        $this->app->bind(IPaymentWithRegisteredCard::class, IyzicoPaymentService::class);
        //Payment System Ends

        //LOGIN BINDINGS
        $this->app->bind(ILoginService::class, LoginService::class);
        $this->app->when(LoginService::class)
            ->needs(INotification::class)
            ->give(NetGSMSmsNotification::class);

        $this->app->when(BabySitterLoginController::class)->needs(IUserRepository::class)->give(BabySitterRepository::class);
        $this->app->when(ParentLoginController::class)->needs(IUserRepository::class)->give(ParentRepository::class);
        //Login Ends

        //BabySitterProfile
        $this->app->when(BabySitterProfileController::class)->needs(IProfileService::class)->give(BabySitterProfileService::class);
        $this->app->when(BabySitterProfileService::class)->needs(IBabySitterRepository::class)->give(BabySitterRepository::class);
        $this->app->when(BabySitterProfileService::class)->needs(IUserRepository::class)->give(BabySitterRepository::class);
        $this->app->bind(IBabySitterRepository::class, BabySitterRepository::class);
        //BabySitter Profile Ends

        //ParentIP
        $this->app->when(ParentProfileController::class)->needs(IProfileService::class)->give(ParentProfileService::class);
        $this->app->when(ParentProfileService::class)->needs(IUserRepository::class)->give(ParentRepository::class);
        //Parent Profile Ends

        //Calendar
        $this->app->bind(IBabySitterCalendarRepository::class, CalendarRepository::class);
        $this->app->bind(IBabySitterCalendarService::class, BabySitterCalendarService::class);
        //Calendar Ends

        //Appointment
        $this->app->bind(IAppointmentRepository::class, AppointmentRepository::class);
        $this->app->bind(IAppointmentService::class, AppointmentService::class);


        //Children

        $this->app->when(ProfileController::class)->needs(IChangableActiveStatus::class)->give(BabySitterProfileService::class);

//Command for babysitter deactivation
        $this->app->when(DeactivateBabySitters::class)
            ->needs(INotification::class)
            ->give(NetGSMSmsNotification::class);

        $this->app->bind(IPointService::class,PointService::class);

    }
}
