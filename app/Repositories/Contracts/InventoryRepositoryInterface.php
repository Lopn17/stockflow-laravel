<?php

namespace App\Repositories\Contracts;

use App\Models\InventoryTransaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InventoryRepositoryInterface
{
    public function history(array $filters = []): LengthAwarePaginator;
    public function createTransaction(array $data): InventoryTransaction;
    public function getStockMovement(int $days = 30): array;
}