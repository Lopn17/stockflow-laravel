<x-app-layout>
    <x-page-header :title="$supplier->company_name" description="Supplier profile">
        <x-slot name="actions">
            @can('update', $supplier)
            <a href="{{ route('suppliers.edit', $supplier) }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="ti ti-pencil"></i> Edit
            </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Supplier info --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-6">
            <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center mb-4">
                <i class="ti ti-truck text-blue-600 text-2xl"></i>
            </div>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Company</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $supplier->company_name }}</dd>
                </div>
                @if($supplier->contact_name)
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Contact</dt>
                    <dd class="text-gray-700 dark:text-gray-300">{{ $supplier->contact_name }}</dd>
                </div>
                @endif
                @if($supplier->email)
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Email</dt>
                    <dd><a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:underline">{{ $supplier->email }}</a></dd>
                </div>
                @endif
                @if($supplier->phone)
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Phone</dt>
                    <dd class="text-gray-700 dark:text-gray-300">{{ $supplier->phone }}</dd>
                </div>
                @endif
                @if($supplier->address)
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Address</dt>
                    <dd class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $supplier->address }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Products from this supplier --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
                    Products ({{ $supplier->products->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($supplier->products as $product)
                <div class="px-6 py-3 flex items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('products.show', $product) }}"
                           class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600 transition-colors">
                            {{ $product->name }}
                        </a>
                        <p class="text-xs text-gray-400 font-mono">{{ $product->sku }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $product->current_stock }} units
                        </p>
                        <p class="text-xs text-gray-400">${{ number_format($product->selling_price, 2) }}</p>
                    </div>
                    <div>
                        @if($product->isOutOfStock())
                            <x-badge color="red">Out of Stock</x-badge>
                        @elseif($product->isLowStock())
                            <x-badge color="yellow">Low Stock</x-badge>
                        @else
                            <x-badge color="green">In Stock</x-badge>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-sm text-gray-400">
                    No products from this supplier yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>