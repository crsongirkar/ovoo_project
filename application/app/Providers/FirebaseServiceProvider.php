<?php

namespace App\Providers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Messaging;
use Illuminate\Support\ServiceProvider;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('firebase', function () {
            return (new Factory)
                ->withServiceAccount(config('firebase.credentials.file'))
                ->withDatabaseUri(config('firebase.database_uri'));
        });

        $this->app->bind(Auth::class, function () {
            return app('firebase')->createAuth();
        });

        $this->app->bind(Messaging::class, function () {
            return app('firebase')->createMessaging();
        });
    }

    public function boot()
    {
        //
    }
}