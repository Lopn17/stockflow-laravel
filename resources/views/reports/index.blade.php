<x-app-layout>
    <x-page-header title="Reports" description="Inventory and stock movement reports">
        <x-slot name="actions">
            <a href="{{ route('reports.csv') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <i class="ti ti-download"></i> Export CSV
            </a>
        </x-slot>
    </x-page-header>

    {{-- Summary stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($inventoryReport) }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Products</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                ${{ number_format(collect($inventoryReport)->sum('stock_value'), 2) }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Total Stock Value</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 text-center">
            <p class="text-2xl font-bold text-amber-600">
                {{ collect($inventoryReport)->where('status', 'Low Stock')->count() }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Low Stock</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 text-center">
            <p class="text-2xl font-bold text-red-600">
                {{ collect($inventoryReport)->where('status', 'Out of Stock')->count() }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Out of Stock</p>
        </div>
    </div>

    {{-- Full report table --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-400">Product</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">SKU</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Category</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Stock</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Min</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Purchase</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Selling</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Value</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($inventoryReport as $row)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">{{ $row['name'] }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $row['sku'] }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $row['category'] }}</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">{{ $row['current_stock'] }}</td>
                    <td class="px-4 py-3 text-right text-gray-500">{{ $row['minimum_stock'] }}</td>
                    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">${{ number_format($row['purchase_price'], 2) }}</td>
                    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">${{ number_format($row['selling_price'], 2) }}</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">${{ number_format($row['stock_value'], 2) }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($row['status'] === 'Out of Stock')
                            <x-badge color="red">Out of Stock</x-badge>
                        @elseif($row['status'] === 'Low Stock')
                            <x-badge color="yellow">Low Stock</x-badge>
                        @else
                            <x-badge color="green">OK</x-badge>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center text-gray-400 text-sm">
                        No data available.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>