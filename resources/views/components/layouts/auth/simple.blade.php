<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }">
    <head>
        @include('partials.head')
        
        <!-- Lucide Icons para iconos del tema -->
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
        
        <style>
            * {
                transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, color 0.2s ease-in-out;
            }
            
            /* Estilos básicos para inputs Flux sin !important */
            input[data-flux-control],
            textarea[data-flux-control],
            select[data-flux-control] {
                width: 100%;
                padding: 12px 16px;
                font-size: 14px;
                border-radius: 8px;
                background: white;
                color: #111827;
                border: 2px solid #d1d5db;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease-in-out;
            }
            
            .dark input[data-flux-control],
            .dark textarea[data-flux-control],
            .dark select[data-flux-control] {
                background: #374151;
                color: #f3f4f6;
                border-color: #6b7280;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
            }
            
            input[data-flux-control]:hover,
            textarea[data-flux-control]:hover,
            select[data-flux-control]:hover {
                border-color: #9ca3af;
            }
            
            .dark input[data-flux-control]:hover,
            .dark textarea[data-flux-control]:hover,
            .dark select[data-flux-control]:hover {
                border-color: #9ca3af;
            }
            
            input[data-flux-control]:focus,
            textarea[data-flux-control]:focus,
            select[data-flux-control]:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            }
        </style>
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-gray-900" x-effect="localStorage.setItem('darkMode', darkMode)">
        <div class="min-h-screen flex">
            <!-- Left Side - Login Form -->
            <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="flex items-center justify-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="building" class="w-7 h-7 text-white"></i>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Sistema de Ingresos y Egresos
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Ingresa tus credenciales para acceder al sistema
                        </p>
                    </div>

                    <!-- Theme Toggle -->
                    <div class="flex justify-center mb-6">
                        <button @click="darkMode = !darkMode" 
                                class="p-3 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 border border-gray-300 dark:border-gray-600">
                            <i data-lucide="sun" class="w-5 h-5" x-show="darkMode"></i>
                            <i data-lucide="moon" class="w-5 h-5" x-show="!darkMode"></i>
                        </button>
                    </div>

                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>

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
                                    <p class="mt-1 text-xs opacity-75">Usa estas credenciales para acceder al sistema</p>
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
                                Sistema de Ingresos y Egresos
                            </h2>
                            <p class="text-lg text-blue-100 mb-8">
                                Administra los ingresos y egresos de tu empresa de manera eficiente con control completo de transacciones financieras.
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
            // Función para inicializar iconos
            function initializeLucideIcons() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                    console.log('Lucide icons initialized');
                } else {
                    console.log('Lucide not available yet');
                }
            }
            
            // Inicializar iconos cuando el DOM esté listo
            document.addEventListener('DOMContentLoaded', function() {
                initializeLucideIcons();
                
                // Reinicializar después de un delay para asegurar que Alpine esté listo
                setTimeout(initializeLucideIcons, 200);
            });

            // Re-initialize icons when Alpine updates the DOM
            document.addEventListener('alpine:updated', function() {
                setTimeout(initializeLucideIcons, 50);
            });
            
            // Inicializar también cuando Alpine esté inicializado
            document.addEventListener('alpine:init', function() {
                setTimeout(initializeLucideIcons, 100);
            });
        </script>

        @fluxScripts
    </body>
</html>
