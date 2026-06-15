<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'Electronics', 'Office Supplies', 'Furniture',
            'Cleaning Products', 'Packaging Materials',
            'Safety Equipment', 'Tools & Hardware', 'Beverages',
        ];

        return [
            'name'        => $this->faker->unique()->randomElement($categories),
            'description' => $this->faker->sentence(),
        ];
    }
}