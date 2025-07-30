<!-- Logo -->
<div class="flex h-16 shrink-0 items-center border-b border-gray-200 dark:border-gray-700 px-6">
    <div class="flex items-center space-x-3">
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
            <i data-lucide="building" class="w-5 h-5 text-white"></i>
        </div>
        <h1 class="text-lg font-bold text-gray-900 dark:text-white">Lamda</h1>
    </div>
</div>

<!-- Navigation -->
<nav class="flex flex-1 flex-col px-6 py-6">
    <ul role="list" class="flex flex-1 flex-col gap-y-7">
        <li>
            <ul role="list" class="-mx-2 space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        <i data-lucide="layout-dashboard" class="h-6 w-6 shrink-0"></i>
                        Dashboard
                    </a>
                </li>

                <!-- Transacciones -->
                <li x-data="{ open: {{ request()->routeIs('transacciones.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('transacciones.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        <i data-lucide="receipt" class="h-6 w-6 shrink-0"></i>
                        Transacciones
                        <i data-lucide="chevron-right" class="ml-auto h-5 w-5 shrink-0 transition-transform duration-200" :class="{ 'rotate-90': open }"></i>
                    </button>
                    <ul x-show="open" x-transition class="mt-1 px-2">
                        <li>
                            <a href="{{ route('transacciones.index') }}?tipoFiltro=ingreso" class="group flex gap-x-3 rounded-md py-2 pl-8 pr-2 text-sm leading-6 {{ request()->routeIs('transacciones.index') && request('tipoFiltro') === 'ingreso' ? 'text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i data-lucide="trending-up" class="h-5 w-5 shrink-0"></i>
                                Ingresos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('transacciones.index') }}?tipoFiltro=egreso" class="group flex gap-x-3 rounded-md py-2 pl-8 pr-2 text-sm leading-6 {{ request()->routeIs('transacciones.index') && request('tipoFiltro') === 'egreso' ? 'text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i data-lucide="trending-down" class="h-5 w-5 shrink-0"></i>
                                Egresos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('transacciones.index') }}" class="group flex gap-x-3 rounded-md py-2 pl-8 pr-2 text-sm leading-6 {{ request()->routeIs('transacciones.index') && !request('tipoFiltro') ? 'text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i data-lucide="list" class="h-5 w-5 shrink-0"></i>
                                Todas
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Contactos -->
                <li>
                    <a href="{{ route('contactos.index') }}" 
                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('contactos.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        <i data-lucide="users" class="h-6 w-6 shrink-0"></i>
                        Contactos
                    </a>
                </li>

                <!-- Reportes -->
                <li x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i data-lucide="bar-chart-3" class="h-6 w-6 shrink-0"></i>
                        Reportes
                        <i data-lucide="chevron-right" class="ml-auto h-5 w-5 shrink-0 transition-transform duration-200" :class="{ 'rotate-90': open }"></i>
                    </button>
                    <ul x-show="open" x-transition class="mt-1 px-2">
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-md py-2 pl-8 pr-2 text-sm leading-6 text-gray-600 dark:text-gray-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i data-lucide="pie-chart" class="h-5 w-5 shrink-0"></i>
                                Ventas
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-md py-2 pl-8 pr-2 text-sm leading-6 text-gray-600 dark:text-gray-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i data-lucide="line-chart" class="h-5 w-5 shrink-0"></i>
                                Finanzas
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-md py-2 pl-8 pr-2 text-sm leading-6 text-gray-600 dark:text-gray-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i data-lucide="trending-up" class="h-5 w-5 shrink-0"></i>
                                Tendencias
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        <!-- Settings Section -->
        <li>
            <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider">
                Configuración
            </div>
            <ul role="list" class="-mx-2 mt-2 space-y-1">
                <li>
                    <a href="#" 
                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i data-lucide="settings" class="h-6 w-6 shrink-0"></i>
                        Configuración
                    </a>
                </li>
                <li>
                    <a href="#" 
                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i data-lucide="credit-card" class="h-6 w-6 shrink-0"></i>
                        Métodos de Pago
                    </a>
                </li>
                <li>
                    <a href="#" 
                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i data-lucide="users" class="h-6 w-6 shrink-0"></i>
                        Usuarios
                    </a>
                </li>
            </ul>
        </li>

        <!-- Bottom section -->
        <li class="mt-auto">
            <div class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300">
                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-xs font-medium text-gray-600 dark:text-gray-400">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <span class="truncate">{{ Auth::user()->name ?? 'Usuario' }}</span>
            </div>
        </li>
    </ul>
</nav>
