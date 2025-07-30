<?php

use App\Models\Transaccion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.app-layout')] #[Title('Ver Transacción')] class extends Component {
    public Transaccion $transaccion;

    public function mount($id)
    {
        $this->transaccion = Transaccion::with(['contacto', 'metodoPago'])->findOrFail($id);
    }

    public function back()
    {
        return $this->redirect(route('transacciones.index'));
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
                            {{ $transaccion->folio }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Transacción {{ $transaccion->folio }}
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $transaccion->tipo === 'ingreso' ? 'Ingreso' : 'Egreso' }} de dinero
                </p>
            </div>
            
            <!-- Icono indicativo -->
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg {{ $transaccion->tipo === 'ingreso' ? 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400' }}">
                    <i data-lucide="{{ $transaccion->tipo === 'ingreso' ? 'trending-up' : 'trending-down' }}" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles de la transacción -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="p-6 space-y-6">
            <!-- Información básica -->
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Información básica
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Folio</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaccion->folio }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm
                                {{ $transaccion->tipo === 'ingreso' 
                                    ? 'bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100 border border-green-200 dark:border-green-700' 
                                    : 'bg-red-100 text-red-900 dark:bg-red-800 dark:text-red-100 border border-red-200 dark:border-red-700' }}">
                                {{ $transaccion->tipo === 'ingreso' ? 'Ingreso' : 'Egreso' }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaccion->fecha->format('d/m/Y') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contacto</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $transaccion->contacto?->nombre ?? 'Sin contacto' }}
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Detalles financieros -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Detalles financieros
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaccion->referencia_nombre }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</dt>
                        <dd class="mt-1 text-lg font-semibold {{ $transaccion->tipo === 'ingreso' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            ${{ number_format($transaccion->total, 2, '.', ',') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Método de pago</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaccion->metodoPago->nombre }}</dd>
                    </div>

                    @if($transaccion->referencia_pago)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Referencia de pago</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaccion->referencia_pago }}</dd>
                        </div>
                    @endif

                    @if($transaccion->observaciones)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Observaciones</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaccion->observaciones }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información del sistema -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Información del sistema
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Creado</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaccion->created_at->format('d/m/Y H:i:s') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Última actualización</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaccion->updated_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex justify-between">
            <button type="button" wire:click="back" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                Volver
            </button>
            
            <div class="flex space-x-3">
                <a href="{{ route('transacciones.edit', $transaccion->id) }}" 
                   class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>
