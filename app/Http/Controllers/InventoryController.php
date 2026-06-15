<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\StoreInventoryTransactionRequest;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(
        private InventoryService $inventoryService
    ) {}

    public function history(Request $request): View
    {
        $transactions = $this->inventoryService->getHistory(
            $request->only(['product_id', 'type', 'date_from', 'date_to'])
        );

        return view('inventory.history', [
            'transactions' => $transactions,
            'filters'      => $request->only(['type', 'date_from', 'date_to']),
            'products'     => Product::orderBy('name')->get(),
        ]);
    }

    public function stockIn(
        StoreInventoryTransactionRequest $request,
        Product $product
    ): RedirectResponse {
        $this->authorize('manageStock', \App\Models\InventoryTransaction::class);

        $this->inventoryService->stockIn(
            product:  $product,
            quantity: (int) $request->validated('quantity'),
            notes:    $request->validated('notes'),
            user:     $request->user(),
        );

        return redirect()
            ->back()
            ->with('success', "Added {$request->validated('quantity')} units to {$product->name}.");
    }

    public function stockOut(
        StoreInventoryTransactionRequest $request,
        Product $product
    ): RedirectResponse {
        $this->authorize('manageStock', \App\Models\InventoryTransaction::class);

        try {
            $this->inventoryService->stockOut(
                product:  $product,
                quantity: (int) $request->validated('quantity'),
                notes:    $request->validated('notes'),
                user:     $request->user(),
            );
        } catch (InsufficientStockException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->back()
            ->with('success', "Removed {$request->validated('quantity')} units from {$product->name}.");
    }

    public function adjustment(
        Request $request,
        Product $product
    ): RedirectResponse {
        $this->authorize('manageStock', \App\Models\InventoryTransaction::class);

        $validated = $request->validate([
            'new_stock' => ['required', 'integer', 'min:0'],
            'notes'     => ['nullable', 'string', 'max:500'],
        ]);

        $this->inventoryService->adjustment(
            product:  $product,
            newStock: $validated['new_stock'],
            notes:    $validated['notes'] ?? null,
            user:     $request->user(),
        );

        return redirect()
            ->back()
            ->with('success', "Stock adjusted to {$validated['new_stock']} units for {$product->name}.");
    }
}