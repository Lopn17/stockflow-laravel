@props(['name', 'title'])

<div x-data x-show="$store.modal.active === '{{ $name }}'"
     x-transition:enter="ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
     @click.self="$store.modal.close()">

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-md"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            <button @click="$store.modal.close()" class="text-gray-400 hover:text-gray-600">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="px-6 py-4">{{ $slot }}</div>
    </div>
</div>