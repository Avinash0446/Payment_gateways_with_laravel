<?php

namespace App\Listeners;

use App\Events\UserRegister;
use App\Jobs\SendMails;
use App\Notifications\registerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class sendRegistrationNotification
{

    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(UserRegister $event): void
    {
        Log::info('this is listener', ["this is listener" => $event]);
        SendMails::dispatch($event->user);
    }
}
