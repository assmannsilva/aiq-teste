<?php

namespace App\Providers;

use App\External\FakeStoreClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            FakeStoreClient::class,
            fn() => new FakeStoreClient(new Client([
                'base_uri' => "https://fakestoreapi.com/",
                'timeout' => 15
            ]))
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
