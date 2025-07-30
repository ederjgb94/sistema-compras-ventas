<!-- Header del Modal -->
<div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
    <div class="sm:flex sm:items-start">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
            @if($createType === 'ingreso')
                <x-lucide-trending-up class="h-6 w-6 text-blue-600 dark:text-blue-400" />
            @else
                <x-lucide-trending-down class="h-6 w-6 text-red-600 dark:text-red-400" />
            @endif
        </div>
        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Nuevo {{ $createType === 'ingreso' ? 'Ingreso' : 'Egreso' }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Registra una nueva transacción de {{ $createType === 'ingreso' ? 'ingreso' : 'egreso' }} en el sistema
            </p>
        </div>
    </div>
</div>

<!-- Formulario -->
<form wire:submit="saveNewTransaccion">
    <div class="bg-white dark:bg-gray-800 px-4 pb-4 sm:p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Columna Izquierda -->
            <div class="space-y-4">
                <!-- Fecha -->
                <div>
                    <label for="newFecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Fecha *
                    </label>
                    <input type="date" 
                           id="newFecha"
                           wire:model="newFecha" 
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('newFecha') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Folio -->
                <div>
                    <label for="newFolio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Folio *
                    </label>
                    <input type="text" 
                           id="newFolio"
                           wire:model="newFolio" 
                           readonly
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm bg-gray-50 dark:bg-gray-600 sm:text-sm">
                    @error('newFolio') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Contacto -->
                <div>
                    <label for="newContactoId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Contacto
                    </label>
                    <select wire:model="newContactoId" 
                            id="newContactoId"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Sin contacto</option>
                        @foreach($contactos as $contacto)
                            <option value="{{ $contacto->id }}">{{ $contacto->nombre }}</option>
                        @endforeach
                    </select>
                    @error('newContactoId') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Método de Pago -->
                <div>
                    <label for="newMetodoPagoId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Método de Pago
                    </label>
                    <select wire:model="newMetodoPagoId" 
                            id="newMetodoPagoId"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Seleccionar método</option>
                        @foreach($metodosPago as $metodo)
                            <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('newMetodoPagoId') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="space-y-4">
                <!-- Referencia -->
                <div>
                    <label for="newReferencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Referencia
                    </label>
                    <input type="text" 
                           id="newReferencia"
                           wire:model="newReferencia" 
                           placeholder="Ej: Obra Casa Familiar"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('newReferencia') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Factura -->
                <div>
                    <label for="newFactura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Factura
                    </label>
                    <input type="text" 
                           id="newFactura"
                           wire:model="newFactura" 
                           placeholder="Ej: FC-2024-001"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('newFactura') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Notas -->
                <div>
                    <label for="newNotas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Notas
                    </label>
                    <textarea wire:model="newNotas" 
                              id="newNotas"
                              rows="3" 
                              placeholder="Notas adicionales..."
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                    @error('newNotas') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>
            </div>
        </div>

        <!-- Conceptos -->
        <div class="mt-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white">Conceptos</h4>
                <button type="button" 
                        wire:click="addNewConcepto"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <x-lucide-plus class="h-4 w-4 mr-1" />
                    Agregar Concepto
                </button>
            </div>

            @error('newConceptos') 
                <p class="mb-4 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
            @enderror

            <div class="space-y-3">
                @foreach($newConceptos as $index => $concepto)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <!-- Descripción -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Descripción *
                                </label>
                                <input type="text" 
                                       wire:model="newConceptos.{{ $index }}.descripcion"
                                       wire:change="calculateNewTotals"
                                       placeholder="Descripción del concepto"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error("newConceptos.{$index}.descripcion") 
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Cantidad -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Cantidad *
                                </label>
                                <input type="number" 
                                       wire:model="newConceptos.{{ $index }}.cantidad"
                                       wire:change="calculateNewTotals"
                                       step="0.01" 
                                       min="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error("newConceptos.{$index}.cantidad") 
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Precio Unitario -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Precio Unitario *
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" 
                                           wire:model="newConceptos.{{ $index }}.precio_unitario"
                                           wire:change="calculateNewTotals"
                                           step="0.01" 
                                           min="0.01"
                                           class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                @error("newConceptos.{$index}.precio_unitario") 
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                                @enderror
                            </div>
                        </div>

                        <!-- Subtotal y botón eliminar -->
                        <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Subtotal: 
                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                    ${{ number_format($concepto['subtotal'] ?? 0, 2) }}
                                </span>
                            </div>
                            @if(count($newConceptos) > 1)
                                <button type="button" 
                                        wire:click="removeNewConcepto({{ $index }})"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    <x-lucide-trash-2 class="h-4 w-4" />
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-medium text-gray-900 dark:text-white">Total:</span>
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        ${{ number_format($newTotal, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer del Modal -->
    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="submit" 
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
            <x-lucide-save class="h-4 w-4 mr-2" />
            Guardar {{ $createType === 'ingreso' ? 'Ingreso' : 'Egreso' }}
        </button>
        <button type="button" 
                wire:click="closeCreateModal"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Cancelar
        </button>
    </div>
</form>
