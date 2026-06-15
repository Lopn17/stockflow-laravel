<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryTransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id'       => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'user_id'          => User::inRandomOrder()->first()?->id ?? User::factory(),
            'type'             => $this->faker->randomElement(['stock_in', 'stock_out', 'adjustment']),
            'quantity'         => $this->faker->numberBetween(1, 50),
            'notes'            => $this->faker->optional()->sentence(),
            'transaction_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}