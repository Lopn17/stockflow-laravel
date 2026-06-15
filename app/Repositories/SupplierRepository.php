<?php

namespace App\Repositories;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SupplierRepository
{
    public function all(): Collection
    {
        return Supplier::withCount('products')->orderBy('company_name')->get();
    }

    public function paginated(array $filters = []): LengthAwarePaginator
    {
        return Supplier::withCount('products')
            ->when(
                $filters['search'] ?? null,
                fn($q, $s) => $q->where('company_name', 'like', "%{$s}%")
                               ->orWhere('contact_name', 'like', "%{$s}%")
                               ->orWhere('email', 'like', "%{$s}%")
            )
            ->orderBy('company_name')
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);
        return $supplier->fresh();
    }

    public function delete(Supplier $supplier): bool
    {
        return $supplier->delete();
    }
}