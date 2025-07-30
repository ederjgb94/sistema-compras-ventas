@props(['pageTitle' => '', 'pageDescription' => '', 'headerActions' => ''])

<!DOCTYPE html>
<html lang="es" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }" x-init="Alpine.initTree($el)">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ? $pageTitle . ' - ' : '' }}Lamda - Sistema de Compras y Ventas</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }
        
        .bg-gray-850 { 
            background-color: #1a202c; 
        }
        
        * {
            transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 antialiased" x-effect="localStorage.setItem('darkMode', darkMode)" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 lg:hidden" 
         @click="sidebarOpen = false">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
    </div>

    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 lg:hidden">
        <!-- Mobile sidebar content -->
        @include('components.sidebar-content')
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
        <div class="flex min-h-0 flex-1 flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
            @include('components.sidebar-content')
        </div>
    </div>

    <!-- Main content -->
    <div class="lg:pl-64">
        <!-- Top navigation -->
        <div class="sticky top-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="flex h-16 items-center gap-x-4 px-4 sm:gap-x-6 sm:px-6 lg:px-8">
                <!-- Hamburger button -->
                <button type="button" 
                        class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-300 lg:hidden" 
                        @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 lg:hidden"></div>

                <div class="flex flex-1 items-center justify-between">
                    <!-- Page title and description -->
                    <div class="flex-1">
                        @if(isset($pageTitle) || hasSection('pageTitle'))
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                                @if(isset($pageTitle))
                                    {{ $pageTitle }}
                                @else
                                    @yield('pageTitle')
                                @endif
                            </h1>
                            @if(isset($pageDescription) || hasSection('pageDescription'))
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    @if(isset($pageDescription))
                                        {{ $pageDescription }}
                                    @else
                                        @yield('pageDescription')
                                    @endif
                                </p>
                            @endif
                        @endif
                    </div>

                    <!-- Right side content -->
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Theme toggle -->
                        <button @click="darkMode = !darkMode" 
                                class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <i data-lucide="sun" class="w-5 h-5" x-show="darkMode"></i>
                            <i data-lucide="moon" class="w-5 h-5" x-show="!darkMode"></i>
                        </button>

                        <!-- Header actions slot -->
                        @if($headerActions)
                            {{ $headerActions }}
                        @endif

                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" 
                                    class="flex items-center rounded-full bg-white dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800" 
                                    @click="open = !open">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">
                                        {{ substr(Auth::user()->name ?? 'U', 0, 2) }}
                                    </span>
                                </div>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 @click.away="open = false">
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name ?? 'Usuario' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? 'email@ejemplo.com' }}</p>
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                                    Perfil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i data-lucide="settings" class="w-4 h-4 inline mr-2"></i>
                                    Configuración
                                </a>
                                <div class="border-t border-gray-200 dark:border-gray-700">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content area -->
        <main class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
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

    @livewireScripts
</body>
</html>
