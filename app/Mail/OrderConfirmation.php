<?php

namespace App\Mail;

use App\Models\Shop\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderConfirmation extends Mailable
{
    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thanks! Your order',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'frontend.shop.emails.order',
            with: [
                'order' => $this->order,
                'items' => $this->order->items,
            ],
        );
    }
}
