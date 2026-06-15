<x-app-layout>
    <x-page-header title="Activity Logs" description="Full audit trail of all system actions" />

    {{-- Search --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 mb-4">
        <form method="GET" class="flex gap-3">
            <div class="relative flex-1 max-w-sm">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search actions or descriptions..."
                       class="w-full pl-8 pr-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
            @if(request('search'))
                <a href="{{ route('logs.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 border border-gray-200 rounded-lg transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-400">User</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Action</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Description</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-700 font-semibold text-xs">
                                    {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                </span>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $log->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <x-badge color="gray">{{ $log->action }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">
                        {{ $log->description ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        <span title="{{ $log->created_at->format('M d, Y H:i:s') }}">
                            {{ $log->created_at->diffForHumans() }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <i class="ti ti-list-details text-4xl text-gray-200 dark:text-gray-700 block mb-3"></i>
                        <p class="text-gray-400 text-sm">No activity logged yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</x-app-layout>