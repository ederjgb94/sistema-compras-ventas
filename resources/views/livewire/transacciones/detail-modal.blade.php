<?php

use App\Models\Transaccion;
use Livewire\Volt\Component;

new class extends Component {
    public Transaccion $transaccion;

    public function mount(Transaccion $transaccion)
    {
        $this->transaccion = $transaccion->load(['contacto', 'metodoPago']);
    }

    public function close()
    {
        $this->dispatch('close-detail-modal');
    }
}; ?>

<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Detalles de {{ ucfirst($transaccion->tipo) }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Folio: {{ $transaccion->folio }}
            </p>
        </div>
        <button wire:click="close" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <div class="space-y-6">
        <!-- Información básica -->
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Información General</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Fecha
                    </label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $transaccion->fecha->format('d/m/Y') }}
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Tipo
                    </label>
                    <p class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm
                            {{ $transaccion->tipo === 'ingreso' 
                                ? 'bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100 border border-green-200 dark:border-green-700' 
                                : 'bg-red-100 text-red-900 dark:bg-red-800 dark:text-red-100 border border-red-200 dark:border-red-700' }}">
                            {{ $transaccion->tipo === 'ingreso' ? 'Ingreso' : 'Egreso' }}
                        </span>
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ $transaccion->tipo === 'ingreso' ? 'Cliente' : 'Proveedor' }}
                    </label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $transaccion->contacto?->nombre ?? 'Sin contacto asignado' }}
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Total
                    </label>
                    <p class="mt-1 text-lg font-semibold {{ $transaccion->tipo === 'ingreso' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        ${{ number_format($transaccion->total, 2, '.', ',') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Referencia -->
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Referencia</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Tipo de referencia
                    </label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white capitalize">
                        {{ $transaccion->referencia_tipo }}
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Nombre de referencia
                    </label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $transaccion->referencia_nombre }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Facturación -->
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Facturación</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Tipo de factura
                    </label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white capitalize">
                        {{ $transaccion->factura_tipo }}
                    </p>
                </div>

                @if($transaccion->factura_numero)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Número de factura
                        </label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $transaccion->factura_numero }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Conceptos -->
            @if($transaccion->factura_datos && isset($transaccion->factura_datos['conceptos']))
                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Conceptos
                    </label>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Descripción
                                    </th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Precio Unit.
                                    </th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($transaccion->factura_datos['conceptos'] as $concepto)
                                    <tr>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">
                                            {{ $concepto['descripcion'] }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-white text-right">
                                            {{ number_format($concepto['cantidad'], 2) }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-white text-right">
                                            ${{ number_format($concepto['precio_unitario'], 2) }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-white text-right font-medium">
                                            ${{ number_format($concepto['subtotal'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totales de la factura -->
                    <div class="mt-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($transaccion->factura_datos['subtotal'] ?? 0, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">IVA (16%):</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($transaccion->factura_datos['iva'] ?? 0, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-base font-semibold border-t border-gray-200 dark:border-gray-600 pt-1">
                                <span class="text-gray-900 dark:text-white">Total:</span>
                                <span class="{{ $transaccion->tipo === 'ingreso' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    ${{ number_format($transaccion->factura_datos['total'] ?? 0, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Método de pago -->
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Método de Pago</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Método
                    </label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $transaccion->metodoPago?->nombre ?? 'No especificado' }}
                    </p>
                </div>

                @if($transaccion->referencia_pago)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Referencia de pago
                        </label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $transaccion->referencia_pago }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Observaciones -->
        @if($transaccion->observaciones)
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Observaciones</h4>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    {{ $transaccion->observaciones }}
                </p>
            </div>
        @endif

        <!-- Metadatos -->
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Información del Sistema</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-500 dark:text-gray-400">
                <div>
                    <label class="block font-medium uppercase tracking-wider">
                        Fecha de creación
                    </label>
                    <p class="mt-1">
                        {{ $transaccion->created_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>

                <div>
                    <label class="block font-medium uppercase tracking-wider">
                        Última actualización
                    </label>
                    <p class="mt-1">
                        {{ $transaccion->updated_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de cerrar -->
    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
        <button wire:click="close" 
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            Cerrar
        </button>
    </div>
</div>
