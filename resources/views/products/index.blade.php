<x-app-layout>
    <x-page-header title="Products" description="Manage your product catalog">
        <x-slot name="actions">
            @can('create', App\Models\Product::class)
            <a href="{{ route('products.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="ti ti-plus"></i> Add Product
            </a>
            @endcan
        </x-slot>
    </x-page-header>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 mb-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <div class="relative flex-1 min-w-48">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search name, SKU, barcode..."
                       class="w-full pl-8 pr-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select name="category_id" class="text-sm border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="supplier_id" class="text-sm border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Suppliers</option>
                @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->company_name }}</option>
                @endforeach
            </select>
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} class="rounded">
                Low stock only
            </label>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">Filter</button>
            @if(request()->hasAny(['search','category_id','supplier_id','low_stock']))
                <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 border border-gray-200 rounded-lg transition-colors">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-400">Product</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">SKU</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Category</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Stock</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Price</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            @if($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" class="w-9 h-9 rounded-lg object-cover border border-gray-100">
                            @else
                                <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <i class="ti ti-package text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('products.show', $product) }}" class="font-medium text-gray-900 dark:text-white hover:text-blue-600 transition-colors">
                                    {{ $product->name }}
                                </a>
                                <p class="text-xs text-gray-400">{{ $product->supplier->company_name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $product->sku }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->category->name }}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="font-semibold {{ $product->isOutOfStock() ? 'text-red-600' : ($product->isLowStock() ? 'text-amber-600' : 'text-gray-900 dark:text-white') }}">
                            {{ $product->current_stock }}
                        </span>
                        <span class="text-xs text-gray-400">/ {{ $product->minimum_stock }}</span>
                    </td>
                    <td class="px-4 py-3 text-right text-gray-900 dark:text-white">${{ number_format($product->selling_price, 2) }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($product->isOutOfStock())
                            <x-badge color="red">Out of Stock</x-badge>
                        @elseif($product->isLowStock())
                            <x-badge color="yellow">Low Stock</x-badge>
                        @else
                            <x-badge color="green">In Stock</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('products.show', $product) }}" class="p-1.5 text-gray-400 hover:text-blue-600 rounded transition-colors" title="View">
                                <i class="ti ti-eye text-base"></i>
                            </a>
                            @can('update', $product)
                            <a href="{{ route('products.edit', $product) }}" class="p-1.5 text-gray-400 hover:text-blue-600 rounded transition-colors" title="Edit">
                                <i class="ti ti-pencil text-base"></i>
                            </a>
                            @endcan
                            @can('delete', $product)
                            <form method="POST" action="{{ route('products.destroy', $product) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors" title="Delete">
                                    <i class="ti ti-trash text-base"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <i class="ti ti-package-off text-4xl text-gray-200 dark:text-gray-700 block mb-3"></i>
                        <p class="text-gray-400 text-sm">No products found.</p>
                        @can('create', App\Models\Product::class)
                        <a href="{{ route('products.create') }}" class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-700 font-medium">Add your first product →</a>
                        @endcan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</x-app-layout>