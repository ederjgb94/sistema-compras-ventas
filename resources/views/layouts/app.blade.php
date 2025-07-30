<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Sistema Compras-Ventas') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js para interactividad -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        /* Inter font fallback */
        body {
            font-family: 'Inter', 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
        
        /* Custom gray-850 for dark sidebar */
        .bg-gray-850 { background-color: #1a202c; }
        .border-gray-850 { border-color: #1a202c; }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        /* Sidebar navigation styles */
        .sidebar-nav-item {
            @apply flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150;
            @apply text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700;
        }

        .sidebar-nav-item.active {
            @apply bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 antialiased" x-effect="localStorage.setItem('darkMode', darkMode)">
    <div class="flex h-screen" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="bg-white dark:bg-gray-850 border-r border-gray-200 dark:border-gray-700 hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 z-50">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <!-- Logo/Icon -->
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="building" class="w-5 h-5 text-white"></i>
                    </div>
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Lamda</h1>
                </div>
                
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode" 
                        class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                    <i data-lucide="sun" class="w-5 h-5" x-show="darkMode"></i>
                    <i data-lucide="moon" class="w-5 h-5" x-show="!darkMode"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                <a href="#" class="sidebar-nav-item">
                    <i data-lucide="trending-up" class="w-5 h-5 mr-3"></i>
                    Engagement
                </a>
                <a href="#" class="sidebar-nav-item">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                    Predictive Analytics
                </a>
                <a href="#" class="sidebar-nav-item">
                    <i data-lucide="repeat" class="w-5 h-5 mr-3"></i>
                    Retention Strategies
                </a>
                <a href="#" class="sidebar-nav-item">
                    <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                    Settings
                </a>
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-white">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ Auth::user()->name ?? 'Usuario' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            Admin
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 flex lg:hidden">
            <div class="fixed inset-0 bg-black bg-opacity-25" @click="sidebarOpen = false"></div>
            
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-gray-850 border-r border-gray-200 dark:border-gray-700">
                <!-- Mobile sidebar content -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="building" class="w-5 h-5 text-white"></i>
                        </div>
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Lamda</h1>
                    </div>
                    <button @click="sidebarOpen = false" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="#" class="sidebar-nav-item">
                        <i data-lucide="trending-up" class="w-5 h-5 mr-3"></i>
                        Engagement
                    </a>
                    <a href="#" class="sidebar-nav-item">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                        Predictive Analytics
                    </a>
                    <a href="#" class="sidebar-nav-item">
                        <i data-lucide="repeat" class="w-5 h-5 mr-3"></i>
                        Retention Strategies
                    </a>
                    <a href="#" class="sidebar-nav-item">
                        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                        Settings
                    </a>
                </nav>

                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-white">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ Auth::user()->name ?? 'Usuario' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                Admin
                            </p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 lg:pl-64">
            <!-- Top Header -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-3 sm:px-6 py-3 sm:py-4">
                <!-- Mobile Layout -->
                <div class="sm:hidden">
                    <div class="flex items-start justify-between gap-3">
                        <!-- Left side: Menu + Content -->
                        <div class="flex items-start space-x-3 min-w-0 flex-1">
                            <button @click="sidebarOpen = true" class="p-1.5 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex-shrink-0 mt-0.5">
                                <i data-lucide="menu" class="w-5 h-5"></i>
                            </button>
                            <div class="min-w-0 flex-1">
                                <h1 class="text-base font-semibold text-gray-900 dark:text-white leading-tight">
                                    {{ $pageTitle ?? 'Configuraciones' }}
                                </h1>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-tight mt-1">
                                    {{ $pageDescription ?? 'Panel principal del sistema' }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Right side: Theme toggle + Action button -->
                        <div class="flex items-start space-x-1 flex-shrink-0 mt-0.5">
                            <button @click="darkMode = !darkMode" 
                                    class="p-1.5 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                                <i data-lucide="sun" class="w-4 h-4" x-show="darkMode"></i>
                                <i data-lucide="moon" class="w-4 h-4" x-show="!darkMode"></i>
                            </button>
                            @if(isset($headerActions))
                                <button class="inline-flex items-center px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors duration-150">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Desktop Layout -->
                <div class="hidden sm:flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-4 min-w-0 flex-1">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl lg:text-2xl font-semibold text-gray-900 dark:text-white truncate leading-tight">
                                {{ $pageTitle ?? 'Configuraciones' }}
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 truncate leading-tight">
                                {{ $pageDescription ?? 'Panel principal del sistema' }}
                            </p>
                        </div>
                    </div>

                    <!-- Desktop Header Actions -->
                    <div class="flex items-center space-x-3 flex-shrink-0">
                        @if(isset($headerActions))
                            {{ $headerActions }}
                        @endif
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto bg-gray-50 dark:bg-gray-900 p-3 sm:p-4 lg:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Initialize Lucide icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        // Re-initialize icons for dynamic content
        document.addEventListener('alpine:updated', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
