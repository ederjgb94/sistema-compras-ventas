<?php

use App\Models\Contacto;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.app-layout')] #[Title('Crear Contacto')] class extends Component {
    // Propiedades para el formulario de creación
    public string $tipo = 'cliente';
    public bool $activo = true;
    public string $nombre = '';
    public string $email = '';
    public string $telefono = '';
    public string $rfc = '';
    public string $direccion = '';

    public function save()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'rfc' => 'nullable|string|max:13',
            'direccion' => 'nullable|string|max:500',
            'tipo' => 'required|in:cliente,proveedor,ambos',
            'activo' => 'boolean',
        ]);

        try {
            Contacto::create([
                'nombre' => $this->nombre,
                'email' => $this->email,
                'telefono' => $this->telefono,
                'rfc' => $this->rfc,
                'direccion' => $this->direccion,
                'tipo' => $this->tipo,
                'activo' => $this->activo,
            ]);

            session()->flash('message', 'Contacto creado exitosamente.');
            return $this->redirect(route('contactos.index'));
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el contacto: ' . $e->getMessage());
        }
    }

    public function cancel()
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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Crear nuevo</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Nuevo Contacto</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Completa la información para agregar un nuevo contacto a tu base de datos
                </p>
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
                    <!-- Tipo de contacto -->
                    <div class="sm:col-span-1">
                        <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de contacto <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="tipo" id="tipo" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="cliente">Cliente</option>
                            <option value="proveedor">Proveedor</option>
                            <option value="ambos">Ambos</option>
                        </select>
                        @error('tipo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Estado activo -->
                    <div class="sm:col-span-1 flex items-center pt-6">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="activo" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Contacto activo</span>
                        </label>
                    </div>

                    <!-- Nombre completo -->
                    <div class="sm:col-span-2">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="nombre" id="nombre" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="Nombre de la persona o empresa">
                        @error('nombre') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Información de contacto -->
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Información de contacto
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Email -->
                    <div class="sm:col-span-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email
                        </label>
                        <input type="email" wire:model="email" id="email" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="contacto@empresa.com">
                        @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="sm:col-span-1">
                        <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Teléfono
                        </label>
                        <input type="text" wire:model="telefono" id="telefono" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="55 1234 5678">
                        @error('telefono') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Información fiscal y dirección -->
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Información adicional
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- RFC -->
                    <div class="sm:col-span-1">
                        <label for="rfc" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            RFC
                        </label>
                        <input type="text" wire:model="rfc" id="rfc" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="ABCD123456ABC" maxlength="13">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Formato: 4 letras, 6 números, 3 caracteres
                        </p>
                        @error('rfc') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="sm:col-span-1">
                        <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Dirección
                        </label>
                        <textarea wire:model="direccion" id="direccion" rows="3" 
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                  placeholder="Dirección completa (opcional)"></textarea>
                        @error('direccion') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" 
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Crear Contacto</span>
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
