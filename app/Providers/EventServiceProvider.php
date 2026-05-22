<?php

namespace App\Providers;

use App\Events\UserRegister;
use App\Listeners\sendRegistrationNotification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [UserRegister::class => [sendRegistrationNotification::class],];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
