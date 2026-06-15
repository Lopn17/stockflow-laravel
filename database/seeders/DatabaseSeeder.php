<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,       // users first — products need a user_id
            CategorySeeder::class,   // categories before products
            SupplierSeeder::class,   // suppliers before products
            ProductSeeder::class,    // products last — needs all of the above
        ]);
    }
}