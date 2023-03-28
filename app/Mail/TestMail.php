<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

        public $details;
        public $transaction;
        public $shipping;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $transaction,$shipping)
    {
        $this->details = $details;
        $this->transaction = $transaction;
        $this->shipping = $shipping;


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order Conformation')->view('emails.TestMail');
    }
}
