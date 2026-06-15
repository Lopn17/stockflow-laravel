<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Supplier;
use App\Policies\ProductPolicy;
use App\Policies\SupplierPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Category;
use App\Policies\CategoryPolicy;
use App\Models\InventoryTransaction;
use App\Policies\InventoryPolicy;

class AuthServiceProvider extends ServiceProvider
{

protected $policies = [
    Product::class             => ProductPolicy::class,
    Supplier::class            => SupplierPolicy::class,
    Category::class            => CategoryPolicy::class,
    InventoryTransaction::class => InventoryPolicy::class,
];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}