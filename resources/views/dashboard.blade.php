<x-app-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>
    <x-slot name="pageDescription">Resumen general</x-slot>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <!-- Mobile Tab Selector -->
            <div class="sm:hidden">
                <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Configuración IA</option>
                    <option>Permisos de Usuario</option>
                    <option>Notificaciones</option>
                </select>
            </div>
            
            <!-- Desktop Tab Navigation -->
            <nav class="-mb-px hidden sm:flex space-x-4 lg:space-x-8 overflow-x-auto">
                <a href="#" class="border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm flex items-center">
                    <i data-lucide="settings" class="w-4 h-4 mr-2"></i>
                    <span class="hidden md:inline">Configuración IA</span>
                    <span class="md:hidden">Config. IA</span>
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm dark:text-gray-400 dark:hover:text-gray-300 flex items-center">
                    <i data-lucide="users" class="w-4 h-4 mr-2"></i>
                    <span class="hidden md:inline">Permisos de Usuario</span>
                    <span class="md:hidden">Usuarios</span>
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm dark:text-gray-400 dark:hover:text-gray-300 flex items-center">
                    <i data-lucide="bell" class="w-4 h-4 mr-2"></i>
                    <span class="hidden md:inline">Notificaciones</span>
                    <span class="md:hidden">Notif</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content Cards -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 lg:gap-6">
        <!-- Saldos Overview -->
        <div class="xl:col-span-2 space-y-4 lg:space-y-6">
            <!-- Saldos Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <!-- Saldo Hoy -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Saldo Hoy</p>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white break-all">
                                ${{ number_format($saldoHoy->saldo ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <i data-lucide="dollar-sign" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="text-green-600 dark:text-green-400">↗ ${{ number_format($saldoHoy->ingresos ?? 0, 2) }}</span>
                        <span class="mx-1 sm:mx-2">|</span>
                        <span class="text-red-600 dark:text-red-400">↙ ${{ number_format($saldoHoy->egresos ?? 0, 2) }}</span>
                    </div>
                </div>

                <!-- Saldo Semana -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Esta Semana</p>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white break-all">
                                ${{ number_format($saldoSemana->saldo ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <i data-lucide="calendar-days" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="text-green-600 dark:text-green-400">↗ ${{ number_format($saldoSemana->ingresos ?? 0, 2) }}</span>
                        <span class="mx-1 sm:mx-2">|</span>
                        <span class="text-red-600 dark:text-red-400">↙ ${{ number_format($saldoSemana->egresos ?? 0, 2) }}</span>
                    </div>
                </div>

                <!-- Saldo Mes -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-6 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Este Mes</p>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white break-all">
                                ${{ number_format($saldoMes->saldo ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <i data-lucide="calendar" class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="text-green-600 dark:text-green-400">↗ ${{ number_format($saldoMes->ingresos ?? 0, 2) }}</span>
                        <span class="mx-1 sm:mx-2">|</span>
                        <span class="text-red-600 dark:text-red-400">↙ ${{ number_format($saldoMes->egresos ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Últimas Transacciones -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                        Últimas Transacciones
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                        <span class="hidden sm:inline">Las 10 transacciones más recientes</span>
                        <span class="sm:hidden">Transacciones recientes</span>
                    </p>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($ultimasTransacciones as $transaccion)
                    <a href="{{ route('transacciones.show', $transaccion->id) }}" class="block px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 min-w-0 flex-1">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg {{ $transaccion->tipo === 'ingreso' ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20' }} flex items-center justify-center flex-shrink-0">
                                    @if($transaccion->tipo === 'ingreso')
                                        <i data-lucide="trending-up" class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 dark:text-green-400"></i>
                                    @else
                                        <i data-lucide="trending-down" class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 dark:text-red-400"></i>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">
                                        <span class="hidden sm:inline">{{ $transaccion->folio }} - </span>{{ $transaccion->referencia_nombre }}
                                    </p>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1 sm:space-y-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                                            <span class="truncate">{{ $transaccion->contacto->nombre ?? 'Sin contacto' }}</span>
                                            <span class="hidden sm:inline">•</span>
                                            <span class="truncate">{{ $transaccion->metodoPago->nombre ?? 'Sin método' }}</span>
                                            <span class="hidden sm:inline">•</span>
                                            <span class="whitespace-nowrap">{{ $transaccion->fecha->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right ml-3 flex-shrink-0">
                                <p class="text-xs sm:text-sm font-semibold {{ $transaccion->tipo === 'ingreso' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $transaccion->tipo === 'ingreso' ? '+' : '-' }}${{ number_format($transaccion->total, 2) }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="px-4 sm:px-6 py-6 sm:py-8 text-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 mx-auto bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3">
                            <i data-lucide="receipt" class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400"></i>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="hidden sm:inline">No hay transacciones registradas</span>
                            <span class="sm:hidden">Sin transacciones</span>
                        </p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Profile and Members Section -->
        <div class="space-y-4 lg:space-y-6">
            <!-- Profile Section -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Perfil y miembros</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                        <span class="hidden sm:inline">Simplifica los roles de usuario para un control de acceso seguro y fluido.</span>
                        <span class="sm:hidden">Control de acceso de usuarios</span>
                    </p>
                </div>
                <div class="p-4 sm:p-6">
                    <!-- Profile Card -->
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg mb-4 sm:mb-6">
                        <div class="flex items-center space-x-3 min-w-0 flex-1">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xs sm:text-sm font-medium text-white">
                                    {{ substr(Auth::user()->name ?? 'U', 0, 2) }}
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name ?? 'Borja Loma' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Admin</p>
                            </div>
                        </div>
                        <button class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-xs sm:text-sm font-medium flex-shrink-0">
                            <span class="hidden sm:inline">Editar Perfil</span>
                            <span class="sm:hidden">Editar</span>
                        </button>
                    </div>

                    <!-- Members Table -->
                    <div class="space-y-3">
                        <!-- Table Header -->
                        <div class="hidden sm:grid sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider px-2">
                            <span class="col-span-2 lg:col-span-2 xl:col-span-2">Nombre</span>
                            <span class="hidden lg:block xl:col-span-2">Correo</span>
                            <span class="col-span-1">Rol</span>
                            <span class="col-span-1 text-right">Acciones</span>
                        </div>
                        
                        @php
                            $members = [
                                ['name' => 'Napa Soli', 'email' => 'napa@lamda.com', 'role' => 'Gerente', 'color' => 'from-orange-500 to-red-500'],
                                ['name' => 'Sophia Martinez', 'email' => 'sophia@lamda.com', 'role' => 'Gerente', 'color' => 'from-pink-500 to-purple-500'],
                                ['name' => 'Emily Johnson', 'email' => 'emily@lamda.com', 'role' => 'Analista', 'color' => 'from-blue-500 to-cyan-500'],
                                ['name' => 'David Smith', 'email' => 'smith@lamda.com', 'role' => 'Analista', 'color' => 'from-yellow-500 to-orange-500'],
                                ['name' => 'Mei Lin Chen', 'email' => 'mei@lamda.com', 'role' => 'Analista', 'color' => 'from-purple-500 to-pink-500']
                            ];
                        @endphp
                        
                        @foreach($members as $member)
                        <!-- Mobile Layout -->
                        <div class="sm:hidden bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br {{ $member['color'] }} rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-medium text-white">
                                            {{ substr($member['name'], 0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $member['name'] }}
                                        </p>
                                        <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 rounded-full">
                                            {{ $member['role'] }}
                                        </span>
                                    </div>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 ml-11">
                                {{ $member['email'] }}
                            </p>
                        </div>
                        
                        <!-- Desktop Layout -->
                        <div class="hidden sm:grid sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 items-center py-2 px-2 hover:bg-gray-50 dark:hover:bg-gray-700/30 rounded-lg transition-colors duration-150">
                            <!-- Name Column -->
                            <div class="col-span-2 lg:col-span-2 xl:col-span-2 flex items-center space-x-3 min-w-0">
                                <div class="w-8 h-8 bg-gradient-to-br {{ $member['color'] }} rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-medium text-white">
                                        {{ substr($member['name'], 0, 2) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $member['name'] }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Email Column (hidden on medium screens) -->
                            <div class="hidden lg:block xl:col-span-2 min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ $member['email'] }}
                                </p>
                            </div>
                            
                            <!-- Role Column -->
                            <div class="col-span-1">
                                <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 rounded-full">
                                    {{ $member['role'] }}
                                </span>
                            </div>
                            
                            <!-- Actions Column -->
                            <div class="col-span-1 text-right">
                                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
