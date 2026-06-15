<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics',          'description' => 'Electronic devices and components'],
            ['name' => 'Office Supplies',       'description' => 'Stationery and office consumables'],
            ['name' => 'Furniture',             'description' => 'Office and warehouse furniture'],
            ['name' => 'Cleaning Products',     'description' => 'Cleaning and hygiene supplies'],
            ['name' => 'Packaging Materials',   'description' => 'Boxes, tape, and packing supplies'],
            ['name' => 'Safety Equipment',      'description' => 'PPE and safety gear'],
            ['name' => 'Tools & Hardware',      'description' => 'Hand tools and hardware items'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}