<?php

use App\Models\Transaccion;
use App\Models\Contacto;
use App\Models\MetodoPago;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.app-layout')] #[Title('Crear Transacción')] class extends Component {
    public string $tipo = '';
    
    // Propiedades para el formulario
    public string $fecha = '';
    public ?int $contactoId = null;
    public string $concepto = '';
    public float $total = 0;
    public ?int $metodoPagoId = null;
    public string $referenciaPago = '';
    public string $observaciones = '';

    public function mount($tipo)
    {
        if (!in_array($tipo, ['ingreso', 'egreso'])) {
            abort(404);
        }
        
        $this->tipo = $tipo;
        $this->fecha = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'fecha' => 'required|date',
            'concepto' => 'required|string|max:255',
            'total' => 'required|numeric|min:0.01',
            'metodoPagoId' => 'required|exists:metodos_pago,id',
            'contactoId' => 'nullable|exists:contactos,id',
            'referenciaPago' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string|max:500',
        ]);

        try {
            Transaccion::create([
                'tipo' => $this->tipo,
                'fecha' => $this->fecha,
                'contacto_id' => $this->contactoId,
                'referencia_tipo' => 'otro', // Valor por defecto para la estructura actual
                'referencia_nombre' => $this->concepto,
                'referencia_datos' => null,
                'factura_tipo' => 'manual',
                'factura_numero' => null,
                'factura_datos' => null,
                'factura_archivos' => null,
                'total' => $this->total,
                'metodo_pago_id' => $this->metodoPagoId,
                'referencia_pago' => $this->referenciaPago ?: null,
                'observaciones' => $this->observaciones ?: null,
            ]);

            session()->flash('message', ucfirst($this->tipo) . ' creado exitosamente.');
            return $this->redirect(route('transacciones.index'));
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el ' . $this->tipo . ': ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return $this->redirect(route('transacciones.index'));
    }

    public function with(): array
    {
        return [
            'contactos' => Contacto::where('activo', true)->orderBy('nombre')->get(),
            'metodosPago' => MetodoPago::orderBy('nombre')->get(),
        ];
    }
}; ?>

<div class="max-w-4xl mx-auto">
    <!-- Header con breadcrumb -->
    <div class="mb-8">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('transacciones.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <i data-lucide="credit-card" class="w-3 h-3 mr-2.5"></i>
                        Transacciones
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                            Crear {{ $tipo === 'ingreso' ? 'ingreso' : 'egreso' }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Crear {{ $tipo === 'ingreso' ? 'Nuevo Ingreso' : 'Nuevo Egreso' }}
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $tipo === 'ingreso' ? 'Registra un nuevo ingreso de dinero' : 'Registra un nuevo egreso de dinero' }}
                </p>
            </div>
            
            <!-- Icono indicativo -->
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg {{ $tipo === 'ingreso' ? 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400' }}">
                    <i data-lucide="{{ $tipo === 'ingreso' ? 'trending-up' : 'trending-down' }}" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-md bg-green-50 dark:bg-green-900/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i data-lucide="check-circle" class="h-5 w-5 text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-md bg-red-50 dark:bg-red-900/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i data-lucide="x-circle" class="h-5 w-5 text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
        <form wire:submit="save" class="p-6 space-y-8">
            <!-- Información básica -->
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Información básica
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Fecha -->
                    <div class="sm:col-span-1">
                        <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fecha <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="fecha" id="fecha" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('fecha') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Contacto -->
                    <div class="sm:col-span-1">
                        <label for="contactoId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contacto
                        </label>
                        <select wire:model="contactoId" id="contactoId" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sin contacto</option>
                            @foreach($contactos as $contacto)
                                <option value="{{ $contacto->id }}">{{ $contacto->nombre }}</option>
                            @endforeach
                        </select>
                        @error('contactoId') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Detalles de la transacción -->
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Detalles de la transacción
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Concepto -->
                    <div class="sm:col-span-2">
                        <label for="concepto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Concepto <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="concepto" id="concepto" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="Describe brevemente la transacción">
                        @error('concepto') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Total -->
                    <div class="sm:col-span-1">
                        <label for="total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Total <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" wire:model="total" id="total" step="0.01" min="0.01"
                                   class="pl-7 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0.00">
                        </div>
                        @error('total') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Método de pago -->
                    <div class="sm:col-span-1">
                        <label for="metodoPagoId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Método de pago <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="metodoPagoId" id="metodoPagoId" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Seleccionar método...</option>
                            @foreach($metodosPago as $metodo)
                                <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('metodoPagoId') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Referencia de pago -->
                    <div class="sm:col-span-1">
                        <label for="referenciaPago" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Referencia de pago
                        </label>
                        <input type="text" wire:model="referenciaPago" id="referenciaPago" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="Número de cheque, transferencia, etc.">
                        @error('referenciaPago') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Observaciones -->
                    <div class="sm:col-span-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Observaciones
                        </label>
                        <textarea wire:model="observaciones" id="observaciones" rows="3" 
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                  placeholder="Información adicional sobre la transacción (opcional)"></textarea>
                        @error('observaciones') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button" wire:click="cancel" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    Cancelar
                </button>
                
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white {{ $tipo === 'ingreso' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500' }} border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2" 
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">
                        Crear {{ ucfirst($tipo) }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creando...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
