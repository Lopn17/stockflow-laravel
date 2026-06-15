@props(['type' => 'success', 'message'])

@php
$colors = [
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'error'   => 'bg-red-50 border-red-200 text-red-800',
    'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
];
$icons = [
    'success' => 'ti-circle-check text-green-500',
    'error'   => 'ti-circle-x text-red-500',
    'warning' => 'ti-alert-triangle text-amber-500',
];
@endphp

<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 4000)"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-4 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-lg border shadow-sm text-sm font-medium {{ $colors[$type] }} max-w-sm">
    <i class="ti {{ $icons[$type] }} text-lg flex-shrink-0"></i>
    <span>{{ $message }}</span>
    <button @click="show = false" class="ml-auto opacity-60 hover:opacity-100">
        <i class="ti ti-x text-sm"></i>
    </button>
</div>