<x-app-layout>
    <x-page-header title="Users" description="Manage system users and roles">
        <x-slot name="actions">
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="ti ti-plus"></i> Add User
            </a>
        </x-slot>
    </x-page-header>

    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-400">Name</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Email</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Role</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Joined</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-700 font-semibold text-xs">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <x-badge color="blue">You</x-badge>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $roleColor = match($user->role) {
                                'admin'  => 'red',
                                'staff'  => 'blue',
                                'viewer' => 'gray',
                                default  => 'gray',
                            };
                        @endphp
                        <x-badge :color="$roleColor">{{ ucfirst($user->role) }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('users.edit', $user) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-600 rounded transition-colors" title="Edit">
                                <i class="ti ti-pencil text-base"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors" title="Delete">
                                    <i class="ti ti-trash text-base"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-gray-400 text-sm">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-app-layout>