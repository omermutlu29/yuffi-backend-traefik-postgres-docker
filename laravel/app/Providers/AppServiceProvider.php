<?php

namespace App\Providers;

use App\Interfaces\NotificationInterfaces\INotification;
use App\Interfaces\PaymentInterfaces\IPayableToSubmerchant;
use App\Services\NotificationServices\NetGSMSmsNotification;
use App\Services\NotificationServices\PushNotificationService;
use App\Services\PaymentServices\IyzicoPaymentService;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IPayableToSubmerchant::class,IyzicoPaymentService::class);
    }
}
