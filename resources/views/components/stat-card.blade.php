@props(['title', 'value', 'icon', 'color' => 'blue', 'alert' => false])

@php
$colors = [
    'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-600',   'ring' => 'ring-blue-100'],
    'green'  => ['bg' => 'bg-green-50',  'icon' => 'text-green-600',  'ring' => 'ring-green-100'],
    'amber'  => ['bg' => 'bg-amber-50',  'icon' => 'text-amber-600',  'ring' => 'ring-amber-100'],
    'red'    => ['bg' => 'bg-red-50',    'icon' => 'text-red-600',    'ring' => 'ring-red-100'],
    'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-600', 'ring' => 'ring-purple-100'],
    'teal'   => ['bg' => 'bg-teal-50',   'icon' => 'text-teal-600',   'ring' => 'ring-teal-100'],
];
$c = $colors[$color];
@endphp

<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-5 flex items-center gap-4 {{ $alert ? 'ring-2 ring-amber-200' : '' }}">
    <div class="w-11 h-11 rounded-xl {{ $c['bg'] }} flex items-center justify-center flex-shrink-0">
        <i class="ti {{ $icon }} text-xl {{ $c['icon'] }}"></i>
    </div>
    <div>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
        <p class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $value }}</p>
    </div>
</div>

