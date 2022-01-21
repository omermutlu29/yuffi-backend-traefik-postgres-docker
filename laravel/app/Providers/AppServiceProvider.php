<?php

namespace App\Providers;

use App\Http\Controllers\API\BabySitter\Auth\LoginController as BabySitterLoginController;
use App\Http\Controllers\API\Parent\Auth\LoginController as ParentLoginController;
use App\Interfaces\IRepositories\IBabySitterCalendarRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IBabySitterCalendarService;
use App\Interfaces\IServices\ILoginService;
use App\Interfaces\NotificationInterfaces\INotification;
use App\Interfaces\PaymentInterfaces\IPaymentService;
use App\Interfaces\PaymentInterfaces\IPayToSubMerchantService;
use App\Interfaces\PaymentInterfaces\IThreeDPaymentService;
use App\Repositories\BabySitterRepository;
use App\Repositories\CalendarRepository;
use App\Repositories\ParentRepository;
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
        $this->app->bind(IBabySitterCalendarRepository::class, CalendarRepository::class);
        $this->app->bind(IPaymentService::class, IyzicoDirectPaymentService::class);
        $this->app->bind(IPayToSubMerchantService::class, IyzicoDirectPaymentService::class);
        $this->app->bind(IThreeDPaymentService::class, IyzicoThreeDPaymentService::class);

        //LOGIN BINDINGS
        $this->app->bind(ILoginService::class, LoginService::class);

        $this->app->when(LoginService::class)
            ->needs(INotification::class)
            ->give(NetGSMSmsNotification::class);

        $this->app->when(BabySitterLoginController::class)->needs(IUserRepository::class)->give(BabySitterRepository::class);
        $this->app->when(ParentLoginController::class)->needs(IUserRepository::class)->give(ParentRepository::class);



        $this->app->when(ProfileService::class)->needs(IBabySitterRepository::class)->give(BabySitterRepository::class);
        $this->app->when(ProfileService::class)->needs(IUserRepository::class)->give(BabySitterRepository::class);
        $this->app->when(BabySitterCalendarService::class)->needs(IBabySitterCalendarService::class)->give(BabySitterRepository::class);
    }
}
