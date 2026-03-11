<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
        \App\Models\Trend::class => \App\Policies\TrendPolicy::class,
        \App\Models\Event::class => \App\Policies\EventPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\TicketPurchase::observe(\App\Observers\TicketPurchaseObserver::class);
        \App\Models\Trend::observe(\App\Observers\TrendObserver::class);
    }
}
