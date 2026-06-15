<?php

namespace App\Services;

use App\Events\StockUpdated;
use App\Exceptions\InsufficientStockException;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function __construct(
        private InventoryRepositoryInterface $inventory,
        private ActivityLogService $logger,
    ) {}

    public function stockIn(
        Product $product,
        int $quantity,
        ?string $notes,
        User $user
    ): InventoryTransaction {
        return DB::transaction(function () use ($product, $quantity, $notes, $user) {
            $product->increment('current_stock', $quantity);

            $transaction = $this->inventory->createTransaction([
                'product_id'       => $product->id,
                'user_id'          => $user->id,
                'type'             => 'stock_in',
                'quantity'         => $quantity,
                'notes'            => $notes,
                'transaction_date' => now(),
            ]);

            event(new StockUpdated($product->fresh(), $user));

            $this->logger->log(
                $user,
                'stock_in',
                "Added {$quantity} units to {$product->name}"
            );

            return $transaction;
        });
    }

    public function stockOut(
        Product $product,
        int $quantity,
        ?string $notes,
        User $user
    ): InventoryTransaction {
        if ($product->current_stock < $quantity) {
            throw new InsufficientStockException(
                "Cannot remove {$quantity} units. Only {$product->current_stock} in stock."
            );
        }

        return DB::transaction(function () use ($product, $quantity, $notes, $user) {
            $product->decrement('current_stock', $quantity);

            $transaction = $this->inventory->createTransaction([
                'product_id'       => $product->id,
                'user_id'          => $user->id,
                'type'             => 'stock_out',
                'quantity'         => $quantity,
                'notes'            => $notes,
                'transaction_date' => now(),
            ]);

            event(new StockUpdated($product->fresh(), $user));

            $this->logger->log(
                $user,
                'stock_out',
                "Removed {$quantity} units from {$product->name}"
            );

            return $transaction;
        });
    }

    public function adjustment(
        Product $product,
        int $newStock,
        ?string $notes,
        User $user
    ): InventoryTransaction {
        $difference = $newStock - $product->current_stock;

        return DB::transaction(function () use ($product, $newStock, $difference, $notes, $user) {
            $product->update(['current_stock' => $newStock]);

            $transaction = $this->inventory->createTransaction([
                'product_id'       => $product->id,
                'user_id'          => $user->id,
                'type'             => 'adjustment',
                'quantity'         => $difference,
                'notes'            => $notes ?? "Manual adjustment to {$newStock} units",
                'transaction_date' => now(),
            ]);

            event(new StockUpdated($product->fresh(), $user));

            $this->logger->log(
                $user,
                'stock_adjusted',
                "Adjusted {$product->name} stock to {$newStock} units"
            );

            return $transaction;
        });
    }

    public function getHistory(array $filters = [])
    {
        return $this->inventory->history($filters);
    }

    public function getStockMovementChart(int $days = 30): array
    {
        return $this->inventory->getStockMovement($days);
    }
}