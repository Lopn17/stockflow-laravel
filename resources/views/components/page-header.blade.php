@props(['title', 'description' => null])

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
        @if($description)
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex items-center gap-2">{{ $actions }}</div>
    @endif
</div>