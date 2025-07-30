<?php

use App\Http\Controllers\DashboardController;
use App\Models\Transaccion;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de configuración/settings
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Contactos usando el componente Livewire
    Route::get('/contactos', \App\Livewire\ContactosIndex::class)->name('contactos.index');
    Route::get('/contactos/create', function () {
        return view('livewire.contactos.create');
    })->name('contactos.create');

    // Rutas de Transacciones  
    Route::get('/transacciones', \App\Livewire\TransaccionesIndex::class)->name('transacciones.index');

    Volt::route('/transacciones/create/{tipo}', 'transacciones.create')
        ->name('transacciones.create')
        ->where('tipo', 'ingreso|egreso');

    // Rutas para acciones de transacciones
    Volt::route('/transacciones/{id}', 'transacciones.show')->name('transacciones.show');
    Volt::route('/transacciones/{id}/edit', 'transacciones.edit')->name('transacciones.edit');

    Route::delete('/transacciones/{transaccion}', function (Transaccion $transaccion) {
        try {
            $transaccion->delete();
            return redirect()->route('transacciones.index')->with('message', 'Transacción eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('transacciones.index')->with('error', 'Error al eliminar la transacción: ' . $e->getMessage());
        }
    })->name('transacciones.destroy');    // Ruta de prueba
    Volt::route('test', 'test')->name('test');

    // TODO: Aquí irán las rutas de reportes, etc.
});
