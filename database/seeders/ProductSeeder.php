<?php

namespace Database\Seeders;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Normal products
        Product::factory(30)->create();

        // Guaranteed low stock products for dashboard alerts
        Product::factory(5)->lowStock()->create();

        // Guaranteed out of stock
        Product::factory(3)->outOfStock()->create();

        // Seed some transaction history
        $adminId = User::where('role', 'admin')->first()->id;

        Product::all()->each(function ($product) use ($adminId) {
            InventoryTransaction::factory(rand(2, 8))->create([
                'product_id' => $product->id,
                'user_id'    => $adminId,
            ]);
        });
    }
}