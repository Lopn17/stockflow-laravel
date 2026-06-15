<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Product::class);

        $products = $this->productService->list($request->only([
            'search', 'category_id', 'supplier_id', 'low_stock', 'out_of_stock'
        ]));

        return view('products.index', [
            'products'   => $products,
            'categories' => Category::orderBy('name')->get(),
            'suppliers'  => Supplier::orderBy('company_name')->get(),
            'filters'    => $request->only(['search', 'category_id', 'supplier_id']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Product::class);

        return view('products.create', [
            'categories' => Category::orderBy('name')->get(),
            'suppliers'  => Supplier::orderBy('company_name')->get(),
            'sku'        => $this->productService->generateSku(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $this->productService->create(
            data:  $request->validated(),
            user:  $request->user(),
            image: $request->hasFile('image') ? $request->file('image') : null,
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $this->authorize('view', $product);

        $product->load(['category', 'supplier']);

        $transactions = $product->inventoryTransactions()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('products.show', compact('product', 'transactions'));
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        return view('products.edit', [
            'product'    => $product,
            'categories' => Category::orderBy('name')->get(),
            'suppliers'  => Supplier::orderBy('company_name')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $this->productService->update(
            product: $product,
            data:    $request->validated(),
            user:    $request->user(),
            image:   $request->hasFile('image') ? $request->file('image') : null,
        );

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $this->productService->delete($product, request()->user());

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}