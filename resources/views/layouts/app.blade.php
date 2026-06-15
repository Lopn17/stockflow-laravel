<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false, darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] dark:bg-gray-950 font-sans antialiased">

{{-- Mobile sidebar backdrop --}}
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/40 z-20 lg:hidden"></div>

<div class="flex h-screen overflow-hidden">

    {{-- ========= SIDEBAR ========= --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:z-auto"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <i class="ti ti-stack-2 text-white text-base"></i>
            </div>
            <span class="font-bold text-gray-900 dark:text-white text-lg tracking-tight">StockFlow</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

            <x-nav-link href="{{ route('dashboard') }}" icon="ti-layout-dashboard" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-nav-link>

            <div class="pt-4 pb-1 px-3">
                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Inventory</span>
            </div>

            <x-nav-link href="{{ route('products.index') }}" icon="ti-package" :active="request()->routeIs('products.*')">
                Products
            </x-nav-link>

            <x-nav-link href="{{ route('categories.index') }}" icon="ti-category" :active="request()->routeIs('categories.*')">
                Categories
            </x-nav-link>

            <x-nav-link href="{{ route('suppliers.index') }}" icon="ti-truck" :active="request()->routeIs('suppliers.*')">
                Suppliers
            </x-nav-link>

            <x-nav-link href="{{ route('inventory.history') }}" icon="ti-history" :active="request()->routeIs('inventory.*')">
                Stock History
            </x-nav-link>

            <div class="pt-4 pb-1 px-3">
                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Analytics</span>
            </div>

            <x-nav-link href="{{ route('reports.index') }}" icon="ti-chart-bar" :active="request()->routeIs('reports.*')">
                Reports
            </x-nav-link>

            @if(auth()->user()->isAdmin())
            <div class="pt-4 pb-1 px-3">
                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Admin</span>
            </div>

            <x-nav-link href="{{ route('users.index') }}" icon="ti-users" :active="request()->routeIs('users.*')">
                Users
            </x-nav-link>

            <x-nav-link href="{{ route('logs.index') }}" icon="ti-list-details" :active="request()->routeIs('logs.*')">
                Activity Logs
            </x-nav-link>
            @endif
        </nav>

        {{-- User footer --}}
        <div class="border-t border-gray-100 dark:border-gray-800 p-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-blue-700 font-semibold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                        <i class="ti ti-logout text-lg"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ========= MAIN CONTENT ========= --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-4 lg:px-6 py-4 flex items-center gap-4 flex-shrink-0">

            {{-- Mobile menu button --}}
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="ti ti-menu-2 text-xl"></i>
            </button>

            {{-- Global search --}}
            <div class="flex-1 max-w-md" x-data="globalSearch()">
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="text"
                        x-model="query"
                        @input.debounce.300ms="search()"
                        @keydown.escape="close()"
                        placeholder="Search products, categories..."
                        class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    {{-- Results dropdown --}}
                    <div x-show="results.length > 0"
                         x-transition
                         @click.outside="close()"
                         class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 overflow-hidden">
                        <template x-for="result in results" :key="result.id + result.type">
                            <a :href="result.url"
                               class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <span class="text-xs font-medium px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 capitalize" x-text="result.type"></span>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="result.label"></p>
                                    <p class="text-xs text-gray-400" x-text="result.sublabel"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 ml-auto">
                {{-- Dark mode toggle --}}
                <button @click="darkMode = !darkMode"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="ti ti-sun text-lg" x-show="darkMode"></i>
                    <i class="ti ti-moon text-lg" x-show="!darkMode"></i>
                </button>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">

            {{-- Flash messages --}}
            @if(session('success'))
                <x-toast type="success" :message="session('success')" />
            @endif
            @if(session('error'))
                <x-toast type="error" :message="session('error')" />
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

<script>

document.addEventListener('alpine:init', () => {
    Alpine.store('modal', {
        active: null,
        open(name) { this.active = name; },
        close() { this.active = null; }
    });
});

function globalSearch() {
    return {
        query: '',
        results: [],
        async search() {
            if (this.query.length < 2) { this.results = []; return; }
            const res = await fetch(`/search?q=${encodeURIComponent(this.query)}`);
            this.results = await res.json();
        },
        close() { this.results = []; this.query = ''; }
    }
}
</script>
    @stack('scripts')
</body>
</html>