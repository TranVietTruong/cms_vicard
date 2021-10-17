<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use OneSignal;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout = 30;

    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $data;

    public $userIds;

    public function __construct($data = [], $userIds = [])
    {
        $this->data = $data;
        $this->userIds = $userIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try
        {
            Onesignal::sendNotificationToUser(
                $this->data['message'],
                $this->userIds,
                $url = $this->data['url'],
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings = $this->data['title']
            );
        }
        catch (\Exception $e)
        {
            Log::error('Job SendNotify failed: '. $e->getMessage());
        }
    }
}
