<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->restrictOnDelete();
    $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
    $table->string('sku', 50)->unique();
    $table->string('barcode', 100)->nullable()->index();
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('purchase_price', 12, 2);
    $table->decimal('selling_price', 12, 2);
    $table->unsignedInteger('minimum_stock')->default(5);
    $table->unsignedInteger('current_stock')->default(0);
    $table->string('image_path')->nullable();
    $table->timestamps();
    $table->softDeletes();
    $table->index(['category_id', 'supplier_id']);
    $table->index('current_stock');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
