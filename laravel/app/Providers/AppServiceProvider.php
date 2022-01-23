<?php

namespace App\Providers;

use App\Http\Controllers\API\BabySitter\Auth\LoginController as BabySitterLoginController;
use App\Http\Controllers\API\BabySitter\Auth\ProfileController as BabySitterProfileController;
use App\Http\Controllers\API\Parent\Auth\LoginController as ParentLoginController;
use App\Http\Controllers\API\Parent\Auth\ProfileController as ParentProfileController;
use App\Interfaces\DepositService\IDepositService;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterCalendarRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\IChildrenRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IAppointmentService;
use App\Interfaces\IServices\IBabySitterCalendarService;
use App\Interfaces\IServices\IChildrenService;
use App\Interfaces\IServices\ILoginService;
use App\Interfaces\IServices\IProfileService;
use App\Interfaces\NotificationInterfaces\INotification;
use App\Interfaces\PaymentInterfaces\IPaymentService;
use App\Interfaces\PaymentInterfaces\IPayToSubMerchantService;
use App\Interfaces\PaymentInterfaces\IThreeDPaymentService;
use App\Repositories\AppointmentRepository;
use App\Repositories\BabySitterRepository;
use App\Repositories\CalendarRepository;
use App\Repositories\ChildrenRepository;
use App\Repositories\ParentRepository;
use App\Services\Appointment\AppointmentService;
use App\Services\Calendar\BabySitterCalendarService;
use App\Services\ChildrenService\ChildrenService;
use App\Services\DepositService\DepositServiceService;
use App\Services\LoginService\LoginService;
use App\Services\NotificationServices\NetGSMSmsNotification;
use App\Services\PaymentServices\Iyzico\IyzicoDirectPaymentService;
use App\Services\PaymentServices\Iyzico\IyzicoThreeDPaymentService;
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
        //DEPOSIT
        $this->app->bind(IDepositService::class, DepositServiceService::class);
        //Deposit Ends

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

        //BabySitterProfile
        $this->app->when(BabySitterProfileController::class)->needs(IProfileService::class)->give(BabySitterProfileService::class);
        $this->app->when(BabySitterProfileService::class)->needs(IBabySitterRepository::class)->give(BabySitterRepository::class);
        $this->app->when(BabySitterProfileService::class)->needs(IUserRepository::class)->give(BabySitterRepository::class);
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
        $this->app->bind(IChildrenRepository::class, ChildrenRepository::class);
        $this->app->bind(IChildrenService::class, ChildrenService::class);
    }
}
