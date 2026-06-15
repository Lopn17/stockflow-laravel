<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(public Product $product) {}

    public function via(object $notifiable): array
    {
        return ['database'];   // stored in DB — no mail server needed
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'product_id'     => $this->product->id,
            'product_name'   => $this->product->name,
            'current_stock'  => $this->product->current_stock,
            'minimum_stock'  => $this->product->minimum_stock,
            'message'        => "{$this->product->name} is low on stock ({$this->product->current_stock} remaining)",
        ];
    }
}