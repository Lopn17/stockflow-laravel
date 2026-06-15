<x-app-layout>
    <x-page-header title="Suppliers" description="Manage your product suppliers">
        <x-slot name="actions">
            @can('create', App\Models\Supplier::class)
            <a href="{{ route('suppliers.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="ti ti-plus"></i> Add Supplier
            </a>
            @endcan
        </x-slot>
    </x-page-header>

    {{-- Search --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 mb-4">
        <form method="GET" class="flex gap-3">
            <div class="relative flex-1 max-w-sm">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search suppliers..."
                       class="w-full pl-8 pr-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
            @if(request('search'))
                <a href="{{ route('suppliers.index') }}"
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
                    <th class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-400">Company</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Contact</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Email</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Phone</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Products</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-3">
                        <a href="{{ route('suppliers.show', $supplier) }}"
                           class="font-medium text-gray-900 dark:text-white hover:text-blue-600 transition-colors">
                            {{ $supplier->company_name }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                        {{ $supplier->contact_name ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                        {{ $supplier->email ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                        {{ $supplier->phone ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <x-badge color="blue">{{ $supplier->products_count }}</x-badge>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('suppliers.show', $supplier) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-600 rounded transition-colors" title="View">
                                <i class="ti ti-eye text-base"></i>
                            </a>
                            @can('update', $supplier)
                            <a href="{{ route('suppliers.edit', $supplier) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-600 rounded transition-colors" title="Edit">
                                <i class="ti ti-pencil text-base"></i>
                            </a>
                            @endcan
                            @can('delete', $supplier)
                            <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($supplier->company_name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors" title="Delete">
                                    <i class="ti ti-trash text-base"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <i class="ti ti-truck-off text-4xl text-gray-200 dark:text-gray-700 block mb-3"></i>
                        <p class="text-gray-400 text-sm">No suppliers found.</p>
                        @can('create', App\Models\Supplier::class)
                        <a href="{{ route('suppliers.create') }}"
                           class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Add your first supplier →
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($suppliers->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>
</x-app-layout>