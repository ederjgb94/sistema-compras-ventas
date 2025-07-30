<?php

use App\Models\Transaccion;
use App\Models\Contacto;
use App\Models\MetodoPago;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.app-layout')] #[Title('Transacciones')] class extends Component {
    use WithPagination;

    // Propiedades para filtros y búsqueda
    public string $search = '';
    public string $tipoFiltro = '';
    public string $fechaInicio = '';
    public string $fechaFin = '';
    public string $contactoFiltro = '';
    public string $metodoPagoFiltro = '';
    public string $sortField = 'fecha';
    public string $sortDirection = 'desc';

    // Propiedades para modales
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public bool $showDetailModal = false;
    public ?Transaccion $selectedTransaccion = null;

    public function mount()
    {
        // Establecer fecha de inicio por defecto (último mes)
        $this->fechaInicio = now()->subMonth()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTipoFiltro()
    {
        $this->resetPage();
    }

    public function updatedFechaInicio()
    {
        $this->resetPage();
    }

    public function updatedFechaFin()
    {
        $this->resetPage();
    }

    public function updatedContactoFiltro()
    {
        $this->resetPage();
    }

    public function updatedMetodoPagoFiltro()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    }

    public function openEditModal($transaccionId)

    private function resetNewTransaccionForm()
    {
        $this->newFecha = now()->format('Y-m-d');
        $this->newFolio = '';
        $this->newReferencia = '';
        $this->newFactura = '';
        $this->newContactoId = null;
        $this->newMetodoPagoId = null;
        $this->newNotas = '';
        $this->newConceptos = [
            [
                'descripcion' => '',
                'cantidad' => 1,
                'precio_unitario' => 0,
                'subtotal' => 0
            ]
        ];
        $this->newTotal = 0;
    }

    private function generateNewFolio()
    {
        $prefix = $this->createType === 'ingreso' ? 'ING' : 'EGR';
        $year = now()->year;
        
        $lastTransaccion = Transaccion::where('folio', 'like', "{$prefix}-%")
            ->whereYear('fecha', $year)
            ->orderBy('folio', 'desc')
            ->first();
        
        if ($lastTransaccion) {
            $lastNumber = (int) explode('-', $lastTransaccion->folio)[1];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        $this->newFolio = sprintf('%s-%03d-%d', $prefix, $nextNumber, $year);
    }

    public function addNewConcepto()
    {
        $this->newConceptos[] = [
            'descripcion' => '',
            'cantidad' => 1,
            'precio_unitario' => 0,
            'subtotal' => 0
        ];
    }

    public function removeNewConcepto($index)
    {
        unset($this->newConceptos[$index]);
        $this->newConceptos = array_values($this->newConceptos);
        $this->calculateNewTotals();
    }

    public function calculateNewTotals()
    {
        $this->newTotal = 0;
        
        foreach ($this->newConceptos as $index => $concepto) {
            if (isset($concepto['cantidad']) && isset($concepto['precio_unitario'])) {
                $subtotal = floatval($concepto['cantidad']) * floatval($concepto['precio_unitario']);
                $this->newConceptos[$index]['subtotal'] = $subtotal;
                $this->newTotal += $subtotal;
            }
        }
    }

    public function saveNewTransaccion()
    {
        // Validaciones básicas
        $this->validate([
            'newFecha' => 'required|date',
            'newFolio' => 'required|string|max:255|unique:transacciones,folio',
            'newReferencia' => 'nullable|string|max:255',
            'newFactura' => 'nullable|string|max:255',
            'newContactoId' => 'nullable|exists:contactos,id',
            'newMetodoPagoId' => 'nullable|exists:metodos_pago,id',
            'newNotas' => 'nullable|string',
            'newConceptos' => 'required|array|min:1',
            'newConceptos.*.descripcion' => 'required|string|max:255',
            'newConceptos.*.cantidad' => 'required|numeric|min:0.01',
            'newConceptos.*.precio_unitario' => 'required|numeric|min:0.01',
        ], [
            'newConceptos.required' => 'Debe agregar al menos un concepto.',
            'newConceptos.min' => 'Debe agregar al menos un concepto.',
            'newConceptos.*.descripcion.required' => 'La descripción del concepto es requerida.',
            'newConceptos.*.cantidad.required' => 'La cantidad es requerida.',
            'newConceptos.*.cantidad.min' => 'La cantidad debe ser mayor a 0.',
            'newConceptos.*.precio_unitario.required' => 'El precio unitario es requerido.',
            'newConceptos.*.precio_unitario.min' => 'El precio unitario debe ser mayor a 0.',
        ]);

        // Filtrar conceptos válidos
        $conceptosValidos = array_filter($this->newConceptos, function($concepto) {
            return !empty($concepto['descripcion']) && 
                   $concepto['cantidad'] > 0 && 
                   $concepto['precio_unitario'] > 0;
        });

        if (empty($conceptosValidos)) {
            session()->flash('error', 'Debe agregar al menos un concepto válido.');
            return;
        }

        // Crear la transacción
        Transaccion::create([
            'fecha' => $this->newFecha,
            'tipo' => $this->createType,
            'folio' => $this->newFolio,
            'referencia' => $this->newReferencia,
            'factura' => $this->newFactura,
            'contacto_id' => $this->newContactoId ?: null,
            'metodo_pago_id' => $this->newMetodoPagoId ?: null,
            'conceptos' => array_values($conceptosValidos),
            'total' => $this->newTotal,
            'notas' => $this->newNotas,
        ]);

        $this->closeCreateModal();
        session()->flash('message', 'Transacción creada exitosamente.');
        
        // Refrescar la lista
        $this->resetPage();
    }

    public function openEditModal($transaccionId)
    {
        $this->selectedTransaccion = Transaccion::findOrFail($transaccionId);
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedTransaccion = null;
    }

    public function showDetail($transaccionId)
    {
        $this->selectedTransaccion = Transaccion::findOrFail($transaccionId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedTransaccion = null;
    }

    public function confirmDelete($transaccionId)
    {
        $this->selectedTransaccion = Transaccion::findOrFail($transaccionId);
        $this->showDeleteModal = true;
    }

    public function deleteTransaccion()
    {
        if ($this->selectedTransaccion) {
            // TODO: Eliminar archivos asociados si existen
            
            $this->selectedTransaccion->delete();
            
            session()->flash('message', 'Transacción eliminada exitosamente.');
            
            $this->showDeleteModal = false;
            $this->selectedTransaccion = null;
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->selectedTransaccion = null;
    }

    // Listener para refrescar la lista cuando se crea/edita una transacción
    protected function getListeners()
    {
        return [
            'transaccion-created' => '$refresh',
            'transaccion-updated' => '$refresh',
            'close-create-modal' => 'closeCreateModal',
            'close-edit-modal' => 'closeEditModal',
        ];
    }

    public function with(): array
    {
        $query = Transaccion::with(['contacto', 'metodoPago']);

        // Aplicar búsqueda general
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('folio', 'LIKE', "%{$this->search}%")
                  ->orWhere('referencia_nombre', 'LIKE', "%{$this->search}%")
                  ->orWhere('factura_numero', 'LIKE', "%{$this->search}%")
                  ->orWhere('observaciones', 'LIKE', "%{$this->search}%")
                  ->orWhereHas('contacto', function ($contacto) {
                      $contacto->where('nombre', 'LIKE', "%{$this->search}%");
                  })
                  ->orWhere(function ($conceptos) {
                      $conceptos->buscarEnConceptos($this->search);
                  });
            });
        }

        // Aplicar filtro por tipo
        if ($this->tipoFiltro) {
            if ($this->tipoFiltro === 'ingreso') {
                $query->ingresos();
            } elseif ($this->tipoFiltro === 'egreso') {
                $query->egresos();
            }
        }

        // Aplicar filtro por fechas
        if ($this->fechaInicio && $this->fechaFin) {
            $query->porFecha($this->fechaInicio, $this->fechaFin);
        } elseif ($this->fechaInicio) {
            $query->whereDate('fecha', '>=', $this->fechaInicio);
        } elseif ($this->fechaFin) {
            $query->whereDate('fecha', '<=', $this->fechaFin);
        }

        // Aplicar filtro por contacto
        if ($this->contactoFiltro) {
            $query->where('contacto_id', $this->contactoFiltro);
        }

        // Aplicar filtro por método de pago
        if ($this->metodoPagoFiltro) {
            $query->where('metodo_pago_id', $this->metodoPagoFiltro);
        }

        // Aplicar ordenamiento
        $query->orderBy($this->sortField, $this->sortDirection);

        return [
            'transacciones' => $query->paginate(15),
            'contactos' => Contacto::orderBy('nombre')->get(),
            'metodosPago' => MetodoPago::activos()->orderBy('nombre')->get(),
            'totalIngresos' => $this->calcularTotal('ingreso'),
            'totalEgresos' => $this->calcularTotal('egreso'),
        ];
    }

    private function calcularTotal($tipo)
    {
        $query = Transaccion::query();

        // Aplicar mismo filtro de fechas que la consulta principal
        if ($this->fechaInicio && $this->fechaFin) {
            $query->porFecha($this->fechaInicio, $this->fechaFin);
        } elseif ($this->fechaInicio) {
            $query->whereDate('fecha', '>=', $this->fechaInicio);
        } elseif ($this->fechaFin) {
            $query->whereDate('fecha', '<=', $this->fechaFin);
        }

        if ($tipo === 'ingreso') {
            $query->ingresos();
        } else {
            $query->egresos();
        }

        return $query->sum('total') ?? 0;
    }
}; ?>

<div>
    <!-- Header con título y botones crear -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Transacciones</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Gestiona ingresos y egresos de tu empresa
            </p>
        </div>
        
        <div class="flex space-x-3">
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
                    <p class="text-2xl font-semibold {{ ($totalIngresos - $totalEgresos) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        ${{ number_format($totalIngresos - $totalEgresos, 2, '.', ',') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Búsqueda general -->
            <div class="lg:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm" placeholder="Buscar por folio, referencia, factura...">
                </div>
            </div>

            <!-- Filtro por tipo -->
            <div>
                <select wire:model.live="tipoFiltro" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                    <option value="">Todos los tipos</option>
                    <option value="ingreso">Ingresos</option>
                    <option value="egreso">Egresos</option>
                </select>
            </div>

            <!-- Fecha inicio -->
            <div>
                <input type="date" wire:model.live="fechaInicio" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
            </div>

            <!-- Fecha fin -->
            <div>
                <input type="date" wire:model.live="fechaFin" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
            </div>

            <!-- Filtro por contacto -->
            <div>
                <select wire:model.live="contactoFiltro" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                    <option value="">Todos los contactos</option>
                    @foreach($contactos as $contacto)
                        <option value="{{ $contacto->id }}">{{ $contacto->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito -->
    @if (session()->has('message'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg mb-6">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabla de transacciones -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button 
                                wire:click="sortBy('fecha')" 
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200"
                            >
                                <span>Fecha</span>
                                @if($sortField === 'fecha')
                                    <i data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                @endif
                            </button>
                        </th>
                        <th class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button 
                                wire:click="sortBy('folio')" 
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200"
                            >
                                <span>Folio</span>
                                @if($sortField === 'folio')
                                    <i data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                @endif
                            </button>
                        </th>
                        <th class="w-20 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button 
                                wire:click="sortBy('tipo')" 
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-200"
                            >
                                <span>Tipo</span>
                                @if($sortField === 'tipo')
                                    <i data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                @endif
                            </button>
                        </th>
                        <th class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Contacto / Referencia
                        </th>
                        <th class="w-32 px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button 
                                wire:click="sortBy('total')" 
                                class="flex items-center justify-end space-x-1 hover:text-gray-700 dark:hover:text-gray-200 w-full"
                            >
                                <span>Total</span>
                                @if($sortField === 'total')
                                    <i data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4"></i>
                                @endif
                            </button>
                        </th>
                        <th class="w-24 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transacciones as $transaccion)
                        <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="w-24 px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $transaccion->fecha->format('d/m/Y') }}
                            </td>
                            <td class="w-32 px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $transaccion->folio }}
                                </div>
                                @if($transaccion->factura_numero)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        F: {{ $transaccion->factura_numero }}
                                    </div>
                                @endif
                            </td>
                            <td class="w-20 px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm
                                    {{ $transaccion->tipo === 'ingreso' 
                                        ? 'bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100 border border-green-200 dark:border-green-700' 
                                        : 'bg-red-100 text-red-900 dark:bg-red-800 dark:text-red-100 border border-red-200 dark:border-red-700' }}">
                                    {{ $transaccion->tipo === 'ingreso' ? 'Ingreso' : 'Egreso' }}
                                </span>
                            </td>
                            <td class="w-1/4 px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $transaccion->contacto?->nombre ?? 'Sin contacto' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    {{ $transaccion->referencia_nombre ?? 'Sin referencia' }}
                                </div>
                            </td>
                            <td class="w-32 px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <span class="{{ $transaccion->tipo === 'ingreso' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    ${{ number_format($transaccion->total, 2, '.', ',') }}
                                </span>
                            </td>
                            <td class="w-24 px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <button wire:click="showDetail({{ $transaccion->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Ver detalles">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <button wire:click="openEditModal({{ $transaccion->id }})" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="Editar">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $transaccion->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
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

    <!-- Modal Crear Transacción -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="create-modal">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeCreateModal"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                Crear {{ ucfirst($createType) }}
                            </h3>
                            <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 px-6 py-4">
                        <form wire:submit.prevent="saveTransaction">
                            <!-- Información básica -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Fecha <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" wire:model="newFecha" id="fecha" 
                                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                                </div>

                                <div>
                                    <label for="contacto_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Contacto
                                    </label>
                                    <select wire:model="newContactoId" id="contacto_id" 
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                                        <option value="">Sin contacto</option>
                                        @foreach($contactos as $contacto)
                                            <option value="{{ $contacto->id }}">{{ $contacto->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Concepto y total simplificado -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="concepto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Concepto <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="newConcepto" id="concepto" 
                                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm"
                                           placeholder="Describe brevemente el concepto">
                                </div>

                                <div>
                                    <label for="total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Total <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" wire:model="newTotal" id="total" step="0.01" min="0"
                                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm"
                                           placeholder="0.00">
                                </div>
                            </div>

                            <!-- Método de pago -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="metodo_pago_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Método de pago <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="newMetodoPagoId" id="metodo_pago_id" 
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                                        <option value="">Seleccionar método...</option>
                                        @foreach($metodosPago as $metodo)
                                            <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="referencia_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Referencia de pago
                                    </label>
                                    <input type="text" wire:model="newReferenciaPago" id="referencia_pago" 
                                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm"
                                           placeholder="Ej: Cheque #1234, Transferencia, etc.">
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="mb-6">
                                <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Observaciones
                                </label>
                                <textarea wire:model="newObservaciones" id="observaciones" rows="3" 
                                          class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm"
                                          placeholder="Observaciones adicionales..."></textarea>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" wire:click="closeCreateModal" 
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-{{ $createType === 'ingreso' ? 'green' : 'red' }}-600 hover:bg-{{ $createType === 'ingreso' ? 'green' : 'red' }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $createType === 'ingreso' ? 'green' : 'red' }}-500">
                                    Crear {{ ucfirst($createType) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Editar Transacción -->
    @if($showEditModal && $selectedTransaccion)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="edit-modal-{{ $selectedTransaccion->id }}">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeEditModal"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <livewire:transacciones.edit-modal :transaccion="$selectedTransaccion" :key="'edit-'.$selectedTransaccion->id" />
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detalles de Transacción -->
    @if($showDetailModal && $selectedTransaccion)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="detail-modal-{{ $selectedTransaccion->id }}">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeDetailModal"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <livewire:transacciones.detail-modal :transaccion="$selectedTransaccion" :key="'detail-'.$selectedTransaccion->id" />
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Confirmar Eliminación -->
    @if($showDeleteModal && $selectedTransaccion)
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
                                ¿Estás seguro de que deseas eliminar la transacción 
                                <strong>{{ $selectedTransaccion->folio }}</strong>? 
                                Esta acción no se puede deshacer.
                            </p>
                        </div>

                        <div class="flex space-x-3">
                            <button wire:click="cancelDelete" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                Cancelar
                            </button>
                            <button wire:click="deleteTransaccion" class="flex-1 px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
