<?php

namespace App\Providers;

use App\Events\CardUpdated;
use App\Listeners\LogCardHistory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if ($this->app->environment('local')) {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }

        Event::listen(CardUpdated::class, LogCardHistory::class);
    }
}
