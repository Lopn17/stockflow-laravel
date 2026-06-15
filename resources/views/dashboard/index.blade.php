<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    @php
    $stockValue =
        $stats['stock_value'] >= 1000000
            ? '$' . number_format($stats['stock_value'] / 1000000, 1) . 'M'
            : '$' . number_format($stats['stock_value'] / 1000, 1) . 'K';
    @endphp

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <x-stat-card title="Total Products"  :value="$stats['total_products']"   icon="ti-package"          color="blue" />
        <x-stat-card title="Categories"      :value="$stats['total_categories']" icon="ti-category"         color="purple" />
        <x-stat-card title="Suppliers"       :value="$stats['total_suppliers']"  icon="ti-truck"            color="teal" />
        <x-stat-card
            title="Stock Value"
            :value="$stockValue"
            icon="ti-currency-dollar"
            color="green"
        />
        <x-stat-card title="Low Stock"       :value="$stats['low_stock_count']"  icon="ti-alert-triangle"   color="amber" :alert="$stats['low_stock_count'] > 0" />
        <x-stat-card title="Out of Stock"    :value="$stats['out_of_stock']"     icon="ti-circle-x"         color="red"   :alert="$stats['out_of_stock'] > 0" />
    </div>

    {{-- Charts row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Stock movement chart --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Stock Movement — Last 30 Days</h3>
            <canvas id="stockChart" height="80"></canvas>
        </div>

        {{-- Top products chart --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Top 5 Products by Value</h3>
            <canvas id="topChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Low stock alerts --}}
        @if($lowStockProducts->isNotEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-amber-200 dark:border-amber-800 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="ti ti-alert-triangle text-amber-500 text-lg"></i>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Low Stock Alerts</h3>
                <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-medium px-2 py-0.5 rounded-full">
                    {{ $lowStockProducts->count() }}
                </span>
            </div>
            <div class="space-y-3">
                @foreach($lowStockProducts as $product)
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $product->category->name }}</p>
                    </div>
                    <div class="text-right flex-shrink-0 ml-3">
                        <span class="text-sm font-bold {{ $product->current_stock === 0 ? 'text-red-600' : 'text-amber-600' }}">
                            {{ $product->current_stock }}
                        </span>
                        <span class="text-xs text-gray-400">/ {{ $product->minimum_stock }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('products.index', ['low_stock' => 1]) }}"
               class="mt-4 block text-center text-xs font-medium text-blue-600 hover:text-blue-700">
                View all →
            </a>
        </div>
        @endif

        {{-- Recent transactions --}}
        <div class="lg:col-span-{{ $lowStockProducts->isNotEmpty() ? '2' : '3' }} bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent Transactions</h3>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($recentTransactions as $tx)
                <div class="px-6 py-3 flex items-center gap-4">
                    <x-badge :color="$tx->typeBadgeColor()">{{ $tx->typeLabel() }}</x-badge>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $tx->product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $tx->user->name }} · {{ $tx->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-sm font-semibold {{ $tx->type === 'stock_out' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $tx->type === 'stock_out' ? '-' : '+' }}{{ $tx->quantity }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-sm text-gray-400">No transactions yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Stock movement line chart
        new Chart(document.getElementById('stockChart'), {
            type: 'line',
            data: {
                labels: @json($stockChart['labels']),
                datasets: [
                    {
                        label: 'Stock In',
                        data: @json($stockChart['stock_in']),
                        borderColor: '#22C55E',
                        backgroundColor: 'rgba(34,197,94,0.08)',
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Stock Out',
                        data: @json($stockChart['stock_out']),
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239,68,68,0.08)',
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } },
                interaction: { intersect: false, mode: 'index' },
            }
        });

        // Top products doughnut
        new Chart(document.getElementById('topChart'), {
            type: 'doughnut',
            data: {
                labels: @json($topProducts['labels']),
                datasets: [{
                    data: @json($topProducts['values']),
                    backgroundColor: ['#2563EB','#22C55E','#F59E0B','#EF4444','#8B5CF6'],
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } },
                cutout: '65%',
            }
        });
    </script>
    @endpush
</x-app-layout>