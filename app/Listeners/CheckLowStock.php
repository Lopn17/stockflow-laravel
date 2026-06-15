<?php

namespace App\Listeners;

use App\Events\StockUpdated;
use App\Models\User;
use App\Notifications\LowStockNotification;

class CheckLowStock
{
    public function handle(StockUpdated $event): void
    {
        $product = $event->product;

        if ($product->current_stock <= $product->minimum_stock) {
            User::where('role', 'admin')
                ->each(fn($admin) => $admin->notify(new LowStockNotification($product)));
        }
    }
}