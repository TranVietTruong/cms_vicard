<?php

namespace App\Jobs;

use App\Mail\ForgetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
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
            Mail::to($this->user->email)->send(new ForgetPassword($this->user->full_name, $this->token));

            $user = User::where('id', $this->user->id)->where('status', 1)->first();
            if($user) {
                $user->forget_password_at = Carbon::now('Asia/Ho_Chi_Minh');
                $user->save();
            }

        }
        catch (\Exception $e)
        {
            Log::error('Job SendEmail failed: '. $e->getMessage());
        }
    }
}
