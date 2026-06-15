<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $products,
        private ActivityLogService $logger,
    ) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->products->all($filters);
    }

    public function create(array $data, User $user, ?UploadedFile $image = null): Product
    {
        if ($image) {
            $data['image_path'] = $image->store('products', 'public');
        }

        $product = $this->products->create($data);

        $this->logger->log($user, 'product_created', "Created product: {$product->name}");

        return $product;
    }

    public function update(Product $product, array $data, User $user, ?UploadedFile $image = null): Product
    {
        if ($image) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $image->store('products', 'public');
        }

        $updated = $this->products->update($product, $data);

        $this->logger->log($user, 'product_updated', "Updated product: {$product->name}");

        return $updated;
    }

    public function delete(Product $product, User $user): bool
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $result = $this->products->delete($product);

        $this->logger->log($user, 'product_deleted', "Deleted product: {$product->name}");

        return $result;
    }

    public function generateSku(string $prefix = 'SKU'): string
    {
        do {
            $sku = strtoupper($prefix . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT));
        } while ($this->products->findBySku($sku));

        return $sku;
    }
}