<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Global search (AJAX)
    Route::get('/search', [SearchController::class, 'index'])
        ->name('search');

    // Products
    Route::resource('products', ProductController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Inventory — view history open to all, mutations need staff+
    Route::get('/inventory/history', [InventoryController::class, 'history'])
        ->name('inventory.history');

    Route::middleware('role:admin,staff')->group(function () {
        Route::post('/inventory/stock-in/{product}', [InventoryController::class, 'stockIn'])
            ->name('inventory.stock-in');
        Route::post('/inventory/stock-out/{product}', [InventoryController::class, 'stockOut'])
            ->name('inventory.stock-out');
        Route::post('/inventory/adjustment/{product}', [InventoryController::class, 'adjustment'])
            ->name('inventory.adjustment');
    });

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
    Route::get('/reports/export/csv', [ReportController::class, 'exportExcel'])
        ->name('reports.csv');

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)
            ->except('show');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('logs.index');
    });
});

require __DIR__.'/auth.php';