<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Product;

class ReportService
{
    public function inventoryReport(): array
    {
        return Product::with(['category', 'supplier'])
            ->get()
            ->map(fn($p) => [
                'sku'            => $p->sku,
                'name'           => $p->name,
                'category'       => $p->category->name,
                'supplier'       => $p->supplier->company_name,
                'current_stock'  => $p->current_stock,
                'minimum_stock'  => $p->minimum_stock,
                'purchase_price' => $p->purchase_price,
                'selling_price'  => $p->selling_price,
                'stock_value'    => $p->stockValue(),
                'status'         => $p->isOutOfStock() ? 'Out of Stock'
                                  : ($p->isLowStock() ? 'Low Stock' : 'OK'),
            ])
            ->toArray();
    }

    public function stockMovementReport(string $from, string $to): array
    {
        return InventoryTransaction::with(['product', 'user'])
            ->whereBetween('transaction_date', [$from, $to])
            ->orderBy('transaction_date')
            ->get()
            ->toArray();
    }
}