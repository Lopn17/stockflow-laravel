@props(['color' => 'gray'])

@php
$colors = [
    'green'  => 'bg-green-100 text-green-700',
    'red'    => 'bg-red-100 text-red-700',
    'yellow' => 'bg-amber-100 text-amber-700',
    'blue'   => 'bg-blue-100 text-blue-700',
    'gray'   => 'bg-gray-100 text-gray-700',
];
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$color] ?? $colors['gray'] }}">
    {{ $slot }}
</span>