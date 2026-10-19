<?php

namespace App\Providers;

use Anthropic\Client;
use App\Support\CurrentTenant;
use App\Support\SupportAssistant;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(CurrentTenant::class);

        $this->app->singleton(SupportAssistant::class, fn () => new SupportAssistant(
            new Client(apiKey: config('services.anthropic.key')),
            config('services.anthropic.model'),
            config('services.anthropic.effort'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
