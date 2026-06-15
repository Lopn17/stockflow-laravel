<x-app-layout>
    <x-page-header title="Stock History" description="Full transaction log for all products" />

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 mb-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="type"
                    class="text-sm border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Types</option>
                <option value="stock_in"   {{ request('type') === 'stock_in'   ? 'selected' : '' }}>Stock In</option>
                <option value="stock_out"  {{ request('type') === 'stock_out'  ? 'selected' : '' }}>Stock Out</option>
                <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
            </select>

            <select name="product_id"
                    class="text-sm border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="text-sm border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="text-sm border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['type', 'product_id', 'date_from', 'date_to']))
                <a href="{{ route('inventory.history') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 border border-gray-200 rounded-lg transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-400">Product</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Type</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Quantity</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Notes</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">User</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($transactions as $tx)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-3">
                        <a href="{{ route('products.show', $tx->product) }}"
                           class="font-medium text-gray-900 dark:text-white hover:text-blue-600 transition-colors">
                            {{ $tx->product->name }}
                        </a>
                        <p class="text-xs text-gray-400 font-mono">{{ $tx->product->sku }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <x-badge :color="$tx->typeBadgeColor()">{{ $tx->typeLabel() }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-right font-semibold {{ $tx->type === 'stock_out' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $tx->type === 'stock_out' ? '-' : '+' }}{{ abs($tx->quantity) }}
                    </td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 max-w-xs truncate">
                        {{ $tx->notes ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                        {{ $tx->user->name }}
                    </td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                        {{ $tx->transaction_date->format('M d, Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <i class="ti ti-history-off text-4xl text-gray-200 dark:text-gray-700 block mb-3"></i>
                        <p class="text-gray-400 text-sm">No transactions found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</x-app-layout>