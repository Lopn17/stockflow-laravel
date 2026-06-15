<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function all(): Collection
    {
        return Category::withCount('products')->orderBy('name')->get();
    }

    public function paginated(array $filters = []): LengthAwarePaginator
    {
        return Category::withCount('products')
            ->when(
                $filters['search'] ?? null,
                fn($q, $s) => $q->where('name', 'like', "%{$s}%")
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}