<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class DashboardService
{
    public function getStats(): array
    {
        return [
            'total_products'    => Product::count(),
            'total_categories'  => Category::count(),
            'total_suppliers'   => Supplier::count(),
            'stock_value'       => Product::sum(\DB::raw('current_stock * purchase_price')),
            'low_stock_count'   => Product::lowStock()->count(),
            'out_of_stock'      => Product::outOfStock()->count(),
        ];
    }

    public function getLowStockProducts()
    {
        return Product::with('category')
            ->lowStock()
            ->orderBy('current_stock')
            ->take(10)
            ->get();
    }

    public function getRecentTransactions()
    {
        return InventoryTransaction::with(['product', 'user'])
            ->latest()
            ->take(10)
            ->get();
    }

    public function getStockMovementChart(int $days = 30): array
    {
        $data = InventoryTransaction::selectRaw(
                'DATE(transaction_date) as date,
                 SUM(CASE WHEN type = "stock_in" THEN quantity ELSE 0 END) as stock_in,
                 SUM(CASE WHEN type = "stock_out" THEN quantity ELSE 0 END) as stock_out'
            )
            ->where('transaction_date', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels'   => $data->pluck('date')->toArray(),
            'stock_in' => $data->pluck('stock_in')->toArray(),
            'stock_out'=> $data->pluck('stock_out')->toArray(),
        ];
    }

    public function getTopProductsByValue(): array
    {
        $data = Product::selectRaw('name, (current_stock * purchase_price) as value')
            ->orderByDesc('value')
            ->take(5)
            ->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'values' => $data->pluck('value')->toArray(),
        ];
    }
}