<?php

namespace App\Jobs;

use App\Notifications\registerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SendMails implements ShouldQueue
{
    use Queueable;
    protected $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('this is a job', ['user' => $this->user]);
        $this->user->notify(new registerNotification());
    }
}
