<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\PaymentGatewayInterface;
use App\Services\Payments\StripePaymentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      $this->app->bind(PaymentGatewayInterface::class, StripePaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
