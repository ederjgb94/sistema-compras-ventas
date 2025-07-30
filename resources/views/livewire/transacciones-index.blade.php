@section('pageTitle', 'Transacciones')
@section('pageDescription', 'Gestiona ingresos y egresos de tu empresa')

<div>
    <!-- Header con título y botones crear -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Transacciones</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Gestiona ingresos y egresos de tu empresa
            </p>
        </div>        <div class="flex space-x-3">
            <a href="{{ route('transacciones.create', 'ingreso') }}" 
               class="flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Nuevo Ingreso
            </a>
            <a href="{{ route('transacciones.create', 'egreso') }}" 
               class="flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <i data-lucide="minus" class="w-4 h-4 mr-2"></i>
                Nuevo Egreso
            </a>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-4 h-4 text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Ingresos</p>
                    <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                        ${{ number_format($totalIngresos, 2, '.', ',') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                        <i data-lucide="trending-down" class="w-4 h-4 text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Egresos</p>
                    <p class="text-2xl font-semibold text-red-600 dark:text-red-400">
                        ${{ number_format($totalEgresos, 2, '.', ',') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <i data-lucide="calculator" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Balance</p>
                    <p class="text-2xl font-semibold {{ $balanceTotal >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        ${{ number_format($balanceTotal, 2, '.', ',') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route('transacciones.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Búsqueda general -->
                <div class="lg:col-span-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm" placeholder="Buscar por número, descripción...">
                    </div>
                </div>

                <!-- Filtro por tipo -->
                <div>
                    <select name="tipoFiltro" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                        <option value="">Todos los tipos</option>
                        <option value="ingreso" {{ $tipo_filter === 'ingreso' ? 'selected' : '' }}>Ingresos</option>
                        <option value="egreso" {{ $tipo_filter === 'egreso' ? 'selected' : '' }}>Egresos</option>
                    </select>
                </div>

                <!-- Filtro por método de pago -->
                <div>
                    <select name="metodo_pago" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                        <option value="">Todos los métodos</option>
                        @foreach($metodosPago as $metodo)
                            <option value="{{ $metodo->id }}" {{ $metodo_pago_filter == $metodo->id ? 'selected' : '' }}>
                                {{ $metodo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha inicio -->
                <div>
                    <input type="date" name="fecha_desde" value="{{ $fecha_desde }}" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                </div>

                <!-- Fecha fin -->
                <div>
                    <input type="date" name="fecha_hasta" value="{{ $fecha_hasta }}" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                </div>

                <!-- Botón filtrar -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-sm transition-colors duration-150">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de transacciones -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('transacciones.index', array_merge(request()->query(), ['sort' => 'fecha', 'direction' => $sort === 'fecha' && $direction === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200">
                                <span>Fecha</span>
                                @if($sort === 'fecha')
                                    <i data-lucide="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('transacciones.index', array_merge(request()->query(), ['sort' => 'folio', 'direction' => $sort === 'folio' && $direction === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200">
                                <span>Número</span>
                                @if($sort === 'folio')
                                    <i data-lucide="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('transacciones.index', array_merge(request()->query(), ['sort' => 'tipo', 'direction' => $sort === 'tipo' && $direction === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200">
                                <span>Tipo</span>
                                @if($sort === 'tipo')
                                    <i data-lucide="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Contacto / Descripción
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('transacciones.index', array_merge(request()->query(), ['sort' => 'total', 'direction' => $sort === 'total' && $direction === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center justify-end space-x-1 hover:text-gray-700 dark:hover:text-gray-200">
                                <span>Monto</span>
                                @if($sort === 'total')
                                    <i data-lucide="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transacciones as $transaccion)
                        <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div>{{ $transaccion->fecha->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaccion->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $transaccion->folio }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm
                                    {{ $transaccion->tipo === 'ingreso' 
                                        ? 'bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100 border border-green-200 dark:border-green-700' 
                                        : 'bg-red-100 text-red-900 dark:bg-red-800 dark:text-red-100 border border-red-200 dark:border-red-700' }}">
                                    {{ $transaccion->tipo === 'ingreso' ? 'Ingreso' : 'Egreso' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $transaccion->contacto?->nombre ?? 'Sin contacto' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    {{ $transaccion->referencia_nombre }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <span class="{{ $transaccion->tipo === 'ingreso' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    ${{ number_format($transaccion->total, 2, '.', ',') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('transacciones.show', $transaccion->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Ver detalles">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('transacciones.edit', $transaccion->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="Editar">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('transacciones.destroy', $transaccion->id) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta transacción?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="receipt" class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No hay transacciones</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">
                                        Comienza creando tu primera transacción
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($transacciones->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $transacciones->links() }}
            </div>
        @endif
    </div>
</div>
