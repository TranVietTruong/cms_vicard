<?php

namespace App\Jobs;

use App\Mail\ConfirmOrder;
use App\Models\CodeTag;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
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
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
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
            Mail::to($this->order->email)->send(new ConfirmOrder($this->order->name));

            $order = Order::where('id', $this->order->id)->first();
            if($order) {
                $order->payment_status = 1;
                $order->save();

                $code_tag = new CodeTag();
                $code_tag->code_tag = $order->code_tag;
                $code_tag->save();
            }
        }
        catch (\Exception $e)
        {
            Log::error('Job SendEmail failed: '. $e->getMessage());
        }
    }
}
