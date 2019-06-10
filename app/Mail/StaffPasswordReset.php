<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StaffPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $token;
    public $base_url;
    public function __construct($token,$base_url)
    {
        //
        $this->token = $token;
        $this->base_url = $base_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('Email/StaffPasswordReset')->with(["token"=>$this->token,"base_url"=>$this->base_url]);
    }
}
