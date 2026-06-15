<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(array $filters = []): LengthAwarePaginator
    {
        return Product::query()
            ->with(['category', 'supplier'])
            ->when(
                $filters['search'] ?? null,
                fn($q, $s) => $q->search($s)
            )
            ->when(
                $filters['category_id'] ?? null,
                fn($q, $id) => $q->where('category_id', $id)
            )
            ->when(
                $filters['supplier_id'] ?? null,
                fn($q, $id) => $q->where('supplier_id', $id)
            )
            ->when(
                $filters['low_stock'] ?? false,
                fn($q) => $q->lowStock()
            )
            ->when(
                $filters['out_of_stock'] ?? false,
                fn($q) => $q->outOfStock()
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();
    }

    public function findById(int $id): ?Product
    {
        return Product::with(['category', 'supplier'])->find($id);
    }

    public function findBySku(string $sku): ?Product
    {
        return Product::where('sku', $sku)->first();
    }

    public function getLowStock(): Collection
    {
        return Product::with(['category', 'supplier'])
            ->lowStock()
            ->orderBy('current_stock')
            ->get();
    }

    public function getOutOfStock(): Collection
    {
        return Product::outOfStock()->get();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}