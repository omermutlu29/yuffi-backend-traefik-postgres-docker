<?php

namespace App\Providers;

use App\Http\Controllers\API\BabySitter\Auth\LoginController as BabySitterLoginController;
use App\Http\Controllers\API\Parent\Auth\LoginController as ParentLoginController;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterCalendarRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IAppointmentService;
use App\Interfaces\IServices\IBabySitterCalendarService;
use App\Interfaces\IServices\ILoginService;
use App\Interfaces\NotificationInterfaces\INotification;
use App\Interfaces\PaymentInterfaces\IPaymentService;
use App\Interfaces\PaymentInterfaces\IPayToSubMerchantService;
use App\Interfaces\PaymentInterfaces\IThreeDPaymentService;
use App\Repositories\AppointmentRepository;
use App\Repositories\BabySitterRepository;
use App\Repositories\CalendarRepository;
use App\Repositories\ParentRepository;
use App\Services\Appointment\AppointmentService;
use App\Services\Calendar\BabySitterCalendarService;
use App\Services\LoginService\LoginService;
use App\Services\NotificationServices\NetGSMSmsNotification;
use App\Services\PaymentServices\Iyzico\IyzicoDirectPaymentService;
use App\Services\PaymentServices\Iyzico\IyzicoThreeDPaymentService;
use App\Services\ProfileService\ProfileService;
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
        //Payment Systems
        $this->app->bind(IPaymentService::class, IyzicoDirectPaymentService::class);
        $this->app->bind(IPayToSubMerchantService::class, IyzicoDirectPaymentService::class);
        $this->app->bind(IThreeDPaymentService::class, IyzicoThreeDPaymentService::class);
        //Payment System Ends

        //LOGIN BINDINGS
        $this->app->bind(ILoginService::class, LoginService::class);
        $this->app->when(LoginService::class)
            ->needs(INotification::class)
            ->give(NetGSMSmsNotification::class);
        $this->app->when(BabySitterLoginController::class)->needs(IUserRepository::class)->give(BabySitterRepository::class);
        $this->app->when(ParentLoginController::class)->needs(IUserRepository::class)->give(ParentRepository::class);
        //Login Ends

        $this->app->when(ProfileService::class)->needs(IBabySitterRepository::class)->give(BabySitterRepository::class);
        $this->app->when(ProfileService::class)->needs(IUserRepository::class)->give(BabySitterRepository::class);


        //Calendar
        $this->app->bind(IBabySitterCalendarRepository::class, CalendarRepository::class);
        $this->app->bind(IBabySitterCalendarService::class, BabySitterCalendarService::class);
        //Calendar Ends

        //Appointment
        $this->app->bind(IAppointmentRepository::class, AppointmentRepository::class);
        $this->app->bind(IAppointmentService::class, AppointmentService::class);
    }
}
