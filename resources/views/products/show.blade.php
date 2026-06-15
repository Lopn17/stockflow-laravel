<x-app-layout>
    <x-page-header :title="$product->name" description="Product details and stock history">
        <x-slot name="actions">
            @can('update', $product)
            <a href="{{ route('products.edit', $product) }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="ti ti-pencil"></i> Edit
            </a>
            @endcan
            @can('manageStock', App\Models\InventoryTransaction::class)
            <button @click="$store.modal.open('stock-in')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <i class="ti ti-plus"></i> Stock In
            </button>
            <button @click="$store.modal.open('stock-out')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                <i class="ti ti-minus"></i> Stock Out
            </button>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Product info card --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-6">
            @if($product->image_path)
                <img src="{{ Storage::url($product->image_path) }}" class="w-full h-48 object-cover rounded-lg mb-4 border border-gray-100">
            @else
                <div class="w-full h-48 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center mb-4">
                    <i class="ti ti-package text-5xl text-gray-300"></i>
                </div>
            @endif

            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">SKU</dt>
                    <dd class="font-mono font-medium text-gray-900 dark:text-white">{{ $product->sku }}</dd>
                </div>
                @if($product->barcode)
                <div class="flex justify-between">
                    <dt class="text-gray-500">Barcode</dt>
                    <dd class="font-mono text-gray-900 dark:text-white">{{ $product->barcode }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500">Category</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $product->category->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Supplier</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $product->supplier->company_name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Purchase Price</dt>
                    <dd class="text-gray-900 dark:text-white">${{ number_format($product->purchase_price, 2) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Selling Price</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">${{ number_format($product->selling_price, 2) }}</dd>
                </div>
                <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex justify-between">
                    <dt class="text-gray-500">Current Stock</dt>
                    <dd class="font-bold text-lg {{ $product->isOutOfStock() ? 'text-red-600' : ($product->isLowStock() ? 'text-amber-600' : 'text-green-600') }}">
                        {{ $product->current_stock }}
                        <span class="text-xs text-gray-400 font-normal">/ min {{ $product->minimum_stock }}</span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Stock Value</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">${{ number_format($product->stockValue(), 2) }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-gray-500">Status</dt>
                    <dd>
                        @if($product->isOutOfStock())
                            <x-badge color="red">Out of Stock</x-badge>
                        @elseif($product->isLowStock())
                            <x-badge color="yellow">Low Stock</x-badge>
                        @else
                            <x-badge color="green">In Stock</x-badge>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Transaction history --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Transaction History</h3>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($transactions as $tx)
                <div class="px-6 py-3 flex items-start gap-4">
                    <x-badge :color="$tx->typeBadgeColor()">{{ $tx->typeLabel() }}</x-badge>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900 dark:text-white">
                            <span class="font-semibold {{ $tx->type === 'stock_out' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $tx->type === 'stock_out' ? '-' : '+' }}{{ $tx->quantity }} units
                            </span>
                        </p>
                        @if($tx->notes)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tx->notes }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tx->user->name }} · {{ $tx->transaction_date->format('M d, Y') }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-sm text-gray-400">No transactions yet.</div>
                @endforelse
            </div>
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Stock In Modal --}}
    @can('manageStock', App\Models\InventoryTransaction::class)
    <x-modal name="stock-in" title="Add Stock">
        <form method="POST" action="{{ route('inventory.stock-in', $product) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity *</label>
                    <input type="number" name="quantity" min="1" required
                           class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea name="notes" rows="2"
                              class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="$store.modal.close()" class="px-4 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">Add Stock</button>
                </div>
            </div>
        </form>
    </x-modal>

    {{-- Stock Out Modal --}}
    <x-modal name="stock-out" title="Remove Stock">
        <form method="POST" action="{{ route('inventory.stock-out', $product) }}">
            @csrf
            <p class="text-sm text-gray-500 mb-4">Available: <strong class="text-gray-900 dark:text-white">{{ $product->current_stock }} units</strong></p>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity *</label>
                    <input type="number" name="quantity" min="1" max="{{ $product->current_stock }}" required
                           class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea name="notes" rows="2"
                              class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="$store.modal.close()" class="px-4 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">Remove Stock</button>
                </div>
            </div>
        </form>
    </x-modal>
    @endcan
</x-app-layout>