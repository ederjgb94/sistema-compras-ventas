<?php

use App\Models\Contacto;
use App\Livewire\Forms\ContactoForm;
use Livewire\Volt\Component;

new class extends Component {
    public ContactoForm $form;
    public Contacto $contacto;

    public function mount(Contacto $contacto)
    {
        $this->contacto = $contacto;
        $this->form->setContacto($contacto);
    }

    public function save()
    {
        try {
            $this->form->update();
            
            session()->flash('message', 'Contacto actualizado exitosamente.');
            
            $this->dispatch('contacto-updated');
            $this->dispatch('close-edit-modal');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el contacto: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        $this->dispatch('close-edit-modal');
    }
}; ?>

<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            Editar Contacto
        </h2>
        <button wire:click="cancel" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Formulario -->
    <form wire:submit="save" class="space-y-6">
        <!-- Tipo y Estado -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tipo de contacto
                </label>
                <select wire:model="form.tipo" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="cliente">Cliente</option>
                    <option value="proveedor">Proveedor</option>
                    <option value="ambos">Ambos</option>
                </select>
                @error('form.tipo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center pt-6">
                <label class="flex items-center">
                    <input type="checkbox" wire:model="form.activo" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
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
                <input type="text" wire:model="form.nombre" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Nombre de la persona o empresa">
                @error('form.nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Email
                </label>
                <input type="email" wire:model="form.email" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="contacto@empresa.com">
                @error('form.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Teléfono
                </label>
                <input type="text" wire:model="form.telefono" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="55 1234 5678">
                @error('form.telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- RFC y Dirección -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    RFC
                </label>
                <input type="text" wire:model="form.rfc" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="ABCD123456ABC" maxlength="13">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Formato: 4 letras, 6 números, 3 caracteres
                </p>
                @error('form.rfc') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Dirección
                </label>
                <textarea wire:model="form.direccion" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Dirección completa (opcional)"></textarea>
                @error('form.direccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Información adicional -->
        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                Información del registro
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div>
                    <span class="font-medium">Creado:</span> 
                    {{ $contacto->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <span class="font-medium">Actualizado:</span> 
                    {{ $contacto->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button type="button" wire:click="cancel" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancelar
            </button>
            
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Actualizar Contacto</span>
                <span wire:loading wire:target="save">Actualizando...</span>
            </button>
        </div>
    </form>
</div>
