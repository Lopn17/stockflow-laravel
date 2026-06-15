@props(['href', 'icon', 'active' => false])

<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
          {{ $active
             ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
    <i class="ti {{ $icon }} text-lg flex-shrink-0"></i>
    {{ $slot }}
</a>