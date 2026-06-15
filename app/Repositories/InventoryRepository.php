<?php

namespace App\Repositories;

use App\Models\InventoryTransaction;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function history(array $filters = []): LengthAwarePaginator
    {
        return InventoryTransaction::query()
            ->with(['product', 'user'])
            ->when(
                $filters['product_id'] ?? null,
                fn($q, $id) => $q->where('product_id', $id)
            )
            ->when(
                $filters['type'] ?? null,
                fn($q, $t) => $q->where('type', $t)
            )
            ->when(
                $filters['date_from'] ?? null,
                fn($q, $d) => $q->whereDate('transaction_date', '>=', $d)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn($q, $d) => $q->whereDate('transaction_date', '<=', $d)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();
    }

    public function createTransaction(array $data): InventoryTransaction
    {
        return InventoryTransaction::create($data);
    }

    public function getStockMovement(int $days = 30): array
    {
        return InventoryTransaction::query()
            ->selectRaw('DATE(transaction_date) as date, type, SUM(quantity) as total')
            ->where('transaction_date', '>=', now()->subDays($days))
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get()
            ->toArray();
    }
}