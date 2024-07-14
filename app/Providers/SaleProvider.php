<?php

namespace App\Providers;

use App\Interfaces\iCustomerRepository;
use App\Repositories\CustomerRepository;
use Illuminate\Support\ServiceProvider;

class SaleProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(iCustomerRepository::class, CustomerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
