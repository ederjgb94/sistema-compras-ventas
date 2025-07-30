<?php

use App\Models\Contacto;
use App\Models\MetodoPago;
use App\Models\Transaccion;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    // Propiedades principales
    public string $tipo = 'ingreso';
    
    // Campos básicos
    public string $fecha = '';
    public ?int $contacto_id = null;
    public string $referencia_tipo = 'obra';
    public string $referencia_nombre = '';
    public string $factura_tipo = 'manual';
    public string $factura_numero = '';
    public ?int $metodo_pago_id = null;
    public string $referencia_pago = '';
    public string $observaciones = '';

    // Conceptos dinámicos para factura manual
    public array $conceptos = [];
    
    // Totales calculados
    public float $subtotal = 0;
    public float $iva = 0;
    public float $total = 0;

    // Props
    public function mount($tipo = 'ingreso')
    {
        $this->tipo = $tipo;
        $this->fecha = now()->format('Y-m-d');
        
        // Agregar un concepto inicial
        $this->conceptos = [
            [
                'descripcion' => '',
                'cantidad' => 1,
                'precio_unitario' => 0,
                'subtotal' => 0,
            ]
        ];
    }

    public function agregarConcepto()
    {
        $this->conceptos[] = [
            'descripcion' => '',
            'cantidad' => 1,
            'precio_unitario' => 0,
            'subtotal' => 0,
        ];
    }

    public function eliminarConcepto($index)
    {
        if (count($this->conceptos) > 1) {
            unset($this->conceptos[$index]);
            $this->conceptos = array_values($this->conceptos);
            $this->calcularTotales();
        }
    }

    public function updatedConceptos()
    {
        $this->calcularTotales();
    }

    public function calcularTotales()
    {
        $this->subtotal = 0;
        
        foreach ($this->conceptos as &$concepto) {
            $concepto['subtotal'] = $concepto['cantidad'] * $concepto['precio_unitario'];
            $this->subtotal += $concepto['subtotal'];
        }
        
        $this->iva = $this->subtotal * 0.16; // IVA 16%
        $this->total = $this->subtotal + $this->iva;
    }

    public function save()
    {
        // Validar campos básicos
        $this->validate();

        // Validar conceptos manualmente
        if ($this->factura_tipo === 'manual') {
            foreach ($this->conceptos as $index => $concepto) {
                if (empty($concepto['descripcion'])) {
                    $this->addError("conceptos.{$index}.descripcion", 'La descripción es requerida.');
                    return;
                }
                if ($concepto['cantidad'] <= 0) {
                    $this->addError("conceptos.{$index}.cantidad", 'La cantidad debe ser mayor a 0.');
                    return;
                }
                if ($concepto['precio_unitario'] <= 0) {
                    $this->addError("conceptos.{$index}.precio_unitario", 'El precio unitario debe ser mayor a 0.');
                    return;
                }
            }
        }

        // Calcular totales finales
        $this->calcularTotales();

        // Preparar datos de la factura
        $facturaData = [
            'conceptos' => $this->conceptos,
            'subtotal' => $this->subtotal,
            'iva' => $this->iva,
            'total' => $this->total,
        ];

        // Crear la transacción
        Transaccion::create([
            'tipo' => $this->tipo,
            'fecha' => $this->fecha,
            'contacto_id' => $this->contacto_id,
            'referencia_tipo' => $this->referencia_tipo,
            'referencia_nombre' => $this->referencia_nombre,
            'factura_tipo' => $this->factura_tipo,
            'factura_numero' => $this->factura_numero,
            'factura_datos' => $facturaData,
            'metodo_pago_id' => $this->metodo_pago_id,
            'referencia_pago' => $this->referencia_pago,
            'total' => $this->total,
            'observaciones' => $this->observaciones,
        ]);

        session()->flash('message', ucfirst($this->tipo) . ' creado exitosamente.');

        $this->dispatch('transaccion-created');
        $this->dispatch('close-create-modal');
    }

    public function cancel()
    {
        $this->dispatch('close-create-modal');
    }

    public function with(): array
    {
        return [
            'contactos' => Contacto::orderBy('nombre')->get(),
            'metodosPago' => MetodoPago::activos()->orderBy('nombre')->get(),
        ];
    }
}; ?>

<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Nuevo {{ ucfirst($tipo) }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Registra un {{ $tipo === 'ingreso' ? 'ingreso de dinero' : 'gasto o pago' }} en el sistema
            </p>
        </div>
        <button wire:click="cancel" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Información básica -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Fecha <span class="text-red-500">*</span>
                </label>
                <input type="date" wire:model="fecha" id="fecha" 
                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                @error('fecha') 
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Contacto -->
            <div>
                <label for="contacto_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ $tipo === 'ingreso' ? 'Cliente' : 'Proveedor' }}
                </label>
                <select wire:model="contacto_id" id="contacto_id" 
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                    <option value="">Seleccionar contacto...</option>
                    @foreach($contactos as $contacto)
                        <option value="{{ $contacto->id }}">{{ $contacto->nombre }}</option>
                    @endforeach
                </select>
                @error('contacto_id') 
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                @enderror
            </div>
        </div>

        <!-- Referencia -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tipo de referencia -->
            <div>
                <label for="referencia_tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tipo de referencia <span class="text-red-500">*</span>
                </label>
                <select wire:model="referencia_tipo" id="referencia_tipo" 
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                    <option value="obra">Obra</option>
                    <option value="producto">Producto</option>
                    <option value="servicio">Servicio</option>
                    <option value="otro">Otro</option>
                </select>
                @error('referencia_tipo') 
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Nombre de referencia -->
            <div>
                <label for="referencia_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nombre de referencia <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="referencia_nombre" id="referencia_nombre" 
                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm"
                       placeholder="Ej: Construcción Casa Pérez, Venta de Material, etc.">
                @error('referencia_nombre') 
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                @enderror
            </div>
        </div>

        <!-- Facturación -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Facturación</h4>
            
            <!-- Tipo de factura y número -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="factura_tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipo de factura <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="factura_tipo" id="factura_tipo" 
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                        <option value="manual">Manual (Capturar conceptos)</option>
                        <option value="archivo">Desde archivo (Próximamente)</option>
                    </select>
                    @error('factura_tipo') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="factura_numero" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Número de factura
                    </label>
                    <input type="text" wire:model="factura_numero" id="factura_numero" 
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm"
                           placeholder="Ej: A001, F-2024-001, etc.">
                    @error('factura_numero') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Conceptos (solo para factura manual) -->
            @if($factura_tipo === 'manual')
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">Conceptos</h5>
                        <button type="button" wire:click="agregarConcepto" 
                                class="flex items-center px-3 py-1 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-150">
                            <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                            Agregar concepto
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($conceptos as $index => $concepto)
                            <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                                    <!-- Descripción -->
                                    <div class="md:col-span-5">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                            Descripción
                                        </label>
                                        <input type="text" wire:model="conceptos.{{ $index }}.descripcion" 
                                               class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 dark:text-white"
                                               placeholder="Descripción del concepto">
                                    </div>

                                    <!-- Cantidad -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                            Cantidad
                                        </label>
                                        <input type="number" wire:model="conceptos.{{ $index }}.cantidad" 
                                               wire:keyup="calcularTotales"
                                               class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 dark:text-white"
                                               min="1" step="0.01">
                                    </div>

                                    <!-- Precio unitario -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                            Precio unit.
                                        </label>
                                        <input type="number" wire:model="conceptos.{{ $index }}.precio_unitario" 
                                               wire:keyup="calcularTotales"
                                               class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 dark:text-white"
                                               min="0" step="0.01">
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                            Subtotal
                                        </label>
                                        <input type="text" 
                                               value="${{ number_format($concepto['subtotal'] ?? 0, 2) }}" 
                                               class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded text-sm bg-gray-100 dark:bg-gray-600 dark:text-white" 
                                               readonly>
                                    </div>

                                    <!-- Eliminar -->
                                    <div class="md:col-span-1 flex items-end">
                                        @if(count($conceptos) > 1)
                                            <button type="button" wire:click="eliminarConcepto({{ $index }})" 
                                                    class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totales -->
                    <div class="mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span class="font-medium text-gray-900 dark:text-white">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">IVA (16%):</span>
                                <span class="font-medium text-gray-900 dark:text-white">${{ number_format($iva, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold border-t border-gray-200 dark:border-gray-600 pt-2">
                                <span class="text-gray-900 dark:text-white">Total:</span>
                                <span class="text-{{ $tipo === 'ingreso' ? 'green' : 'red' }}-600 dark:text-{{ $tipo === 'ingreso' ? 'green' : 'red' }}-400">
                                    ${{ number_format($total, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Método de pago -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Método de pago</h4>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="metodo_pago_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Método de pago <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="metodo_pago_id" id="metodo_pago_id" 
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm">
                        <option value="">Seleccionar método...</option>
                        @foreach($metodosPago as $metodo)
                            <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('metodo_pago_id') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="referencia_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Referencia de pago
                    </label>
                    <input type="text" wire:model="referencia_pago" id="referencia_pago" 
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm"
                           placeholder="Ej: Cheque #1234, Transferencia, etc.">
                    @error('referencia_pago') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div>
            <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Observaciones
            </label>
            <textarea wire:model="observaciones" id="observaciones" rows="3" 
                      class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white text-sm"
                      placeholder="Observaciones adicionales..."></textarea>
            @error('observaciones') 
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button type="button" wire:click="cancel" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                Cancelar
            </button>
            <button type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-{{ $tipo === 'ingreso' ? 'green' : 'red' }}-600 hover:bg-{{ $tipo === 'ingreso' ? 'green' : 'red' }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $tipo === 'ingreso' ? 'green' : 'red' }}-500">
                Crear {{ ucfirst($tipo) }}
            </button>
        </div>
    </form>
</div>
