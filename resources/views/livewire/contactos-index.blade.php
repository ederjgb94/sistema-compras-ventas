@section('pageTitle', 'Contactos')
@section('pageDescription', 'Gestiona tu base de datos de clientes y proveedores')

<div>
    <!-- Header con título y botón crear -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Contactos</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Gestiona tu base de datos de clientes y proveedores
            </p>
        </div>        <a href="{{ route('contactos.create') }}" class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Nuevo Contacto
        </a>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route('contactos.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Búsqueda -->
            <div class="lg:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm" placeholder="Buscar por nombre, RFC, email...">
                </div>
            </div>

            <!-- Filtro por tipo -->
            <div>
                <select name="tipo" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                    <option value="">Todos los tipos</option>
                    <option value="cliente" {{ request('tipo') === 'cliente' ? 'selected' : '' }}>Clientes</option>
                    <option value="proveedor" {{ request('tipo') === 'proveedor' ? 'selected' : '' }}>Proveedores</option>
                    <option value="ambos" {{ request('tipo') === 'ambos' ? 'selected' : '' }}>Ambos</option>
                </select>
            </div>

            <!-- Filtro por estado -->
            <div class="flex items-center gap-2">
                <select name="estado" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Mensajes de éxito -->
    @if (session()->has('message'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg mb-6">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabla de contactos -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Vista de tabla para pantallas grandes -->
        <div class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'nombre', 'direction' => request('sort') === 'nombre' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                    class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200">
                                    <span>Nombre</span>
                                    @if(request('sort', 'nombre') === 'nombre')
                                        <i data-lucide="{{ request('direction', 'asc') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'tipo', 'direction' => request('sort') === 'tipo' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                    class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200">
                                    <span>Tipo</span>
                                    @if(request('sort') === 'tipo')
                                        <i data-lucide="{{ request('direction', 'asc') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Email / Teléfono
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                RFC
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'activo', 'direction' => request('sort') === 'activo' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                    class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200">
                                    <span>Estado</span>
                                    @if(request('sort') === 'activo')
                                        <i data-lucide="{{ request('direction', 'asc') === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($contactos as $contacto)
                            <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $contacto->nombre }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm
                                        @if($contacto->tipo === 'cliente') bg-blue-100 text-blue-900 dark:bg-blue-800 dark:text-blue-100 border border-blue-200 dark:border-blue-700
                                        @elseif($contacto->tipo === 'proveedor') bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100 border border-green-200 dark:border-green-700
                                        @else bg-purple-100 text-purple-900 dark:bg-purple-800 dark:text-purple-100 border border-purple-200 dark:border-purple-700
                                        @endif">
                                        {{ ucfirst($contacto->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $contacto->email ?: '-' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        @if($contacto->telefono)
                                            <a href="tel:{{ $contacto->telefono }}" class="inline-flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150" title="Llamar a {{ $contacto->telefono }}">
                                                <i data-lucide="phone" class="w-3 h-3 mr-1"></i>
                                                {{ $contacto->telefono }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $contacto->rfc ?: '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm
                                        {{ $contacto->activo 
                                            ? 'bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100 border border-green-200 dark:border-green-700' 
                                            : 'bg-red-100 text-red-900 dark:bg-red-800 dark:text-red-100 border border-red-200 dark:border-red-700' 
                                        }}">
                                        {{ $contacto->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('contactos.show', $contacto->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Ver detalles">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('contactos.edit', $contacto->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="Editar">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('contactos.destroy', $contacto->id) }}" class="inline" onsubmit="return confirm('{{ $contacto->tieneTransacciones() ? '⚠️ Este contacto tiene ' . $contacto->cantidadTransacciones() . ' transacciones asociadas. Se ocultará pero las transacciones se preservarán. ¿Continuar?' : '¿Estás seguro de que quieres eliminar este contacto?' }}')">
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
                                        <i data-lucide="users" class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No hay contactos</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                                            @if($search || $tipoFiltro || $estadoFiltro !== '')
                                                No se encontraron contactos que coincidan con los filtros aplicados.
                                            @else
                                                Comienza agregando tu primer contacto.
                                            @endif
                                        </p>
                                        <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                            Agregar Contacto
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vista de tarjetas para pantallas pequeñas y medianas -->
        <div class="lg:hidden divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($contactos as $contacto)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $contacto->nombre }}</h3>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        @if($contacto->tipo === 'cliente') bg-blue-100 text-blue-900 dark:bg-blue-800 dark:text-blue-100
                                        @elseif($contacto->tipo === 'proveedor') bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100
                                        @else bg-purple-100 text-purple-900 dark:bg-purple-800 dark:text-purple-100
                                        @endif">
                                        {{ ucfirst($contacto->tipo) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $contacto->activo 
                                            ? 'bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100' 
                                            : 'bg-red-100 text-red-900 dark:bg-red-800 dark:text-red-100' 
                                        }}">
                                        {{ $contacto->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('contactos.show', $contacto->id) }}" class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors" title="Ver detalles">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('contactos.edit', $contacto->id) }}" class="p-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-md transition-colors" title="Editar">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('contactos.destroy', $contacto->id) }}" class="inline" onsubmit="return confirm('{{ $contacto->tieneTransacciones() ? '⚠️ Este contacto tiene ' . $contacto->cantidadTransacciones() . ' transacciones asociadas. Se ocultará pero las transacciones se preservarán. ¿Continuar?' : '¿Estás seguro de que quieres eliminar este contacto?' }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Eliminar">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        @if($contacto->email)
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i data-lucide="mail" class="w-4 h-4 mr-2 text-gray-400"></i>
                                <span>{{ $contacto->email }}</span>
                            </div>
                        @endif
                        
                        @if($contacto->telefono)
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i data-lucide="phone" class="w-4 h-4 mr-2 text-gray-400"></i>
                                <a href="tel:{{ $contacto->telefono }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                                    {{ $contacto->telefono }}
                                </a>
                            </div>
                        @endif
                        
                        @if($contacto->rfc)
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i data-lucide="file-text" class="w-4 h-4 mr-2 text-gray-400"></i>
                                <span>{{ $contacto->rfc }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i data-lucide="users" class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No hay contactos</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                            @if($search || $tipoFiltro || $estadoFiltro !== '')
                                No se encontraron contactos que coincidan con los filtros aplicados.
                            @else
                                Comienza agregando tu primer contacto.
                            @endif
                        </p>
                        <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Agregar Contacto
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($contactos->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $contactos->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Crear Contacto -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="create-modal">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeCreateModal"></div>
                
                <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full z-50">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Crear Nuevo Contacto
                            </h2>
                            <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>

                        <!-- Formulario -->
                        <form wire:submit="saveContacto" class="space-y-6">
                            <!-- Tipo y Estado -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tipo de contacto
                                    </label>
                                    <select wire:model="form_tipo" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="cliente">Cliente</option>
                                        <option value="proveedor">Proveedor</option>
                                        <option value="ambos">Ambos</option>
                                    </select>
                                    @error('form_tipo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex items-center pt-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="form_activo" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Contacto activo</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Información básica -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombre completo
                                    </label>
                                    <input type="text" wire:model="form_nombre" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Nombre de la persona o empresa">
                                    @error('form_nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email
                                    </label>
                                    <input type="email" wire:model="form_email" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="contacto@empresa.com">
                                    @error('form_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Teléfono
                                    </label>
                                    <input type="text" wire:model="form_telefono" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="55 1234 5678">
                                    @error('form_telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- RFC y Dirección -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        RFC
                                    </label>
                                    <input type="text" wire:model="form_rfc" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="ABCD123456ABC" maxlength="13">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Formato: 4 letras, 6 números, 3 caracteres
                                    </p>
                                    @error('form_rfc') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Dirección
                                    </label>
                                    <textarea wire:model="form_direccion" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Dirección completa (opcional)"></textarea>
                                    @error('form_direccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" wire:click="closeCreateModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancelar
                                </button>
                                
                                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="saveContacto">Crear Contacto</span>
                                    <span wire:loading wire:target="saveContacto">Creando...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Editar Contacto -->
    @if($showEditModal && $selectedContacto)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="edit-modal-{{ $selectedContacto->id }}">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeEditModal"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <livewire:contactos.edit-modal :contacto="$selectedContacto" :key="'edit-'.$selectedContacto->id" />
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Confirmar Eliminación -->
    @if($showDeleteModal && $selectedContacto)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="cancelDelete"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20">
                                <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                Confirmar eliminación
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                                ¿Estás seguro de que deseas eliminar el contacto 
                                <strong>{{ $selectedContacto->nombre }}</strong>? 
                                Esta acción no se puede deshacer.
                            </p>
                        </div>

                        <div class="flex space-x-3">
                            <button wire:click="cancelDelete" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                Cancelar
                            </button>
                            <button wire:click="deleteContacto" class="flex-1 px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
