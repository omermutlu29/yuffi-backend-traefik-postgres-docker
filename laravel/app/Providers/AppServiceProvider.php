<?php

namespace App\Providers;

use App\Interfaces\PaymentInterfaces\IPaymentService;
use App\Interfaces\PaymentInterfaces\IPayToSubMerchantService;
use App\Interfaces\PaymentInterfaces\IThreeDPaymentService;
use App\Services\PaymentServices\Iyzico\IyzicoDirectPaymentService;
use App\Services\PaymentServices\Iyzico\IyzicoThreeDPaymentService;
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
        $this->app->bind(IPaymentService::class,IyzicoDirectPaymentService::class);
        $this->app->bind(IPayToSubMerchantService::class,IyzicoDirectPaymentService::class);
        $this->app->bind(IThreeDPaymentService::class,IyzicoThreeDPaymentService::class);
    }
}
