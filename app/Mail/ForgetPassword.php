<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $userName;
    public $token;

    public function __construct($userName, $token)
    {
        $this->subject('VICARD - QUÊN MẬT KHẨU');
        $this->userName = $userName;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.forgetPassword')
            ->with([
                'userName' => $this->userName,
                'token' => $this->token
            ]);
    }
}
