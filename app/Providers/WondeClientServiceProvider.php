<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Wonde\Client;

class WondeClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function() {
            return new Client(config('services.wonde_client.token'));
        });
    }

}
