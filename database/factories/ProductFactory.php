<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $purchasePrice = $this->faker->randomFloat(2, 5, 500);

        return [
            'category_id'    => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'supplier_id'    => Supplier::inRandomOrder()->first()?->id ?? Supplier::factory(),
            'sku'            => strtoupper($this->faker->unique()->bothify('SKU-####-??')),
            'barcode'        => $this->faker->optional()->ean13(),
            'name'           => $this->faker->words(3, true),
            'description'    => $this->faker->paragraph(),
            'purchase_price' => $purchasePrice,
            'selling_price'  => round($purchasePrice * $this->faker->randomFloat(2, 1.2, 2.5), 2),
            'minimum_stock'  => $this->faker->numberBetween(5, 20),
            'current_stock'  => $this->faker->numberBetween(0, 100),
            'image_path'     => null,
        ];
    }

    // State: force a low stock product
    public function lowStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'current_stock' => $this->faker->numberBetween(0, 4),
            'minimum_stock' => 5,
        ]);
    }

    // State: force an out of stock product
    public function outOfStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'current_stock' => 0,
        ]);
    }
}