<?php

namespace App\Providers;

use App\Interfaces\iItemRepository;
use App\Repositories\ItemRepository;
use Illuminate\Support\ServiceProvider;

class PurchaseProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(iItemRepository::class, ItemRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
