<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\SubscriptionCriteria;
use App\Price;

class PriceDropped extends Mailable
{
    use Queueable, SerializesModels;

    public $gender;
    public $size;
    public $price;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SubscriptionCriteria $subscriptionCriteria, $price)
    {
        extract($subscriptionCriteria->toArray());
        $this->gender = $gender;
        $this->size = $size;
        $this->price = Price::displayFormat($price);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('example@example.com')
                    ->subject('Price Changed')
                    ->view('emails.price_dropped');
    }
}
