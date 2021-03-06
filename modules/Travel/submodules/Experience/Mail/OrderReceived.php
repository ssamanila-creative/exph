<?php

namespace Experience\Mail;

use Experience\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * From email.
     *
     * @var string
     */
    // public $from = 'john.dionisio1@gmail.com';

    /**
     * The order instance.
     *
     * @var Order\Models\Order
     */
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // ->from(settings('site_email', config('MAIL_FROM_ADDRESS', 'john.dionisio1@gmail.com')))
        return $this->view('Experience::emails.order-received.index')
                    ->with(['order' => $this->order, 'user' => $this->user]);
    }
}
