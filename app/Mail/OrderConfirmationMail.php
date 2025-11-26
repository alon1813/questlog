<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $invoicePath;

    public function __construct(Order $order, string $invoicePath)
    {
        $this->order = $order->load('items.product');
        $this->invoicePath = $invoicePath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Confirmación de Pedido #' . $this->order->order_number . ' - QuestLog',
            
            replyTo: ['soporte@questlog.com'],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.confirmation',
            with: [
                'order' => $this->order,
                'userName' => $this->order->user->name, 
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->invoicePath)
                ->as('factura-' . $this->order->order_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}