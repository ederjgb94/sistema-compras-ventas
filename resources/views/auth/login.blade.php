<!DOCTYPE html>
<html lang="es" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Compras-Ventas - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 antialiased" x-effect="localStorage.setItem('darkMode', darkMode)">
    <div class="min-h-screen flex">
        <!-- Left Side - Login Form -->
        <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="building" class="w-7 h-7 text-white"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lamda</h1>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Sistema de Gestión Empresarial
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Ingresa tus credenciales para acceder al sistema
                    </p>
                </div>

                <!-- Theme Toggle -->
                <div class="flex justify-center mb-6">
                    <button @click="darkMode = !darkMode" 
                            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <i data-lucide="sun" class="w-5 h-5" x-show="darkMode"></i>
                        <i data-lucide="moon" class="w-5 h-5" x-show="!darkMode"></i>
                    </button>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    Hay errores en el formulario
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('message'))
                    <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5"></i>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 dark:text-green-300">
                                    {{ session('message') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                value="{{ old('email', 'admin@admin.com') }}" 
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200"
                                placeholder="usuario@empresa.com"
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                value="admin"
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200"
                                placeholder="Ingresa tu contraseña"
                            >
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Recordar sesión</span>
                        </label>

                        <a href="#" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-colors duration-200"
                    >
                        Iniciar Sesión
                    </button>
                </form>

                <!-- Información de Acceso -->
                <div class="mt-8 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex">
                        <i data-lucide="key" class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5"></i>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                Credenciales de Acceso
                            </h3>
                            <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                <p><strong>Usuario:</strong> admin@admin.com</p>
                                <p><strong>Contraseña:</strong> admin</p>
                                <p class="mt-1 text-xs opacity-75">Los campos ya están prellenados para tu comodidad</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Decorative -->
        <div class="hidden lg:block relative w-0 flex-1">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-blue-800">
                <!-- Decorative Elements -->
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                
                <!-- Content -->
                <div class="relative h-full flex flex-col justify-center items-center text-white p-12">
                    <div class="max-w-md text-center">
                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-8">
                            <i data-lucide="bar-chart-3" class="w-10 h-10"></i>
                        </div>
                        
                        <h2 class="text-3xl font-bold mb-4">
                            Sistema de Gestión Integral
                        </h2>
                        <p class="text-lg text-blue-100 mb-8">
                            Administra tu empresa de manera eficiente con nuestro sistema completo de compras, ventas y análisis financiero.
                        </p>

                        <!-- Features -->
                        <div class="text-left space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                                </div>
                                <span class="text-blue-100">Control de transacciones en tiempo real</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                </div>
                                <span class="text-blue-100">Gestión de contactos y proveedores</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </div>
                                <span class="text-blue-100">Reportes detallados y análisis</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Decorative Shapes -->
                <div class="absolute top-20 left-20 w-32 h-32 bg-white bg-opacity-10 rounded-full blur-xl"></div>
                <div class="absolute bottom-20 right-20 w-40 h-40 bg-purple-400 bg-opacity-20 rounded-full blur-2xl"></div>
                <div class="absolute top-1/2 left-10 w-24 h-24 bg-blue-300 bg-opacity-15 rounded-full blur-lg"></div>
            </div>
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
