<?php

use App\Models\Contacto;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.app-layout')] #[Title('Ver Contacto')] class extends Component {
    public Contacto $contacto;

    public function mount($id)
    {
        $this->contacto = Contacto::findOrFail($id);
    }

    public function back()
    {
        return $this->redirect(route('contactos.index'));
    }
}; ?>

<div class="max-w-4xl mx-auto">
    <!-- Header con breadcrumb -->
    <div class="mb-8">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('contactos.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <i data-lucide="users" class="w-3 h-3 mr-2.5"></i>
                        Contactos
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                            Ver detalles
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $contacto->nombre }}
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Detalles completos del contacto
                </p>
            </div>
            
            <!-- Icono indicativo -->
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg 
                    {{ $contacto->tipo === 'cliente' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 
                       ($contacto->tipo === 'proveedor' ? 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400' : 
                        'bg-purple-100 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400') }}">
                    <i data-lucide="{{ $contacto->tipo === 'cliente' ? 'user' : ($contacto->tipo === 'proveedor' ? 'truck' : 'users') }}" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del contacto -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <!-- Header con estado -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm
                        {{ $contacto->tipo === 'cliente' ? 'bg-blue-100 text-blue-900 dark:bg-blue-800 dark:text-blue-100 border border-blue-200 dark:border-blue-700' : 
                           ($contacto->tipo === 'proveedor' ? 'bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100 border border-green-200 dark:border-green-700' : 
                            'bg-purple-100 text-purple-900 dark:bg-purple-800 dark:text-purple-100 border border-purple-200 dark:border-purple-700') }}">
                        {{ ucfirst($contacto->tipo) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm
                        {{ $contacto->activo ? 'bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100 border border-green-200 dark:border-green-700' : 
                           'bg-red-100 text-red-900 dark:bg-red-800 dark:text-red-100 border border-red-200 dark:border-red-700' }}">
                        {{ $contacto->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Detalles -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Información básica -->
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Información básica
                    </h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre completo</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $contacto->nombre }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de contacto</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ ucfirst($contacto->tipo) }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $contacto->activo ? 'Activo' : 'Inactivo' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Información de contacto -->
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Información de contacto
                    </h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($contacto->email)
                                    <a href="mailto:{{ $contacto->email }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ $contacto->email }}
                                    </a>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">No especificado</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($contacto->telefono)
                                    <a href="tel:{{ $contacto->telefono }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ $contacto->telefono }}
                                    </a>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">No especificado</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">RFC</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($contacto->rfc)
                                    <span class="font-mono">{{ $contacto->rfc }}</span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">No especificado</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Dirección -->
            @if($contacto->direccion)
                <div class="mt-8">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Dirección
                    </h3>
                    
                    <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        {{ $contacto->direccion }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Botones de acción -->
        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4">
            <div class="flex justify-between">
                <button wire:click="back" type="button" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2 inline"></i>
                    Volver
                </button>
                
                <a href="{{ route('contactos.edit', $contacto->id) }}" 
                   class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i data-lucide="edit" class="w-4 h-4 mr-2 inline"></i>
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script para mantener Lucide funcional -->
<script>
    document.addEventListener('livewire:updated', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
