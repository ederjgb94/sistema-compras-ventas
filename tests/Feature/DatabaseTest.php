<?php

use App\Models\User;
use App\Models\Contacto;
use App\Models\MetodoPago;
use App\Models\Transaccion;
use Illuminate\Support\Facades\Schema;

test('database migrations work correctly', function () {
    // Test de usuario básico
    expect(Schema::hasTable('users'))->toBeTrue();

    // Test de tabla contactos
    expect(Schema::hasTable('contactos'))->toBeTrue();

    // Test de tabla metodos_pago
    expect(Schema::hasTable('metodos_pago'))->toBeTrue();

    // Test de tabla transacciones
    expect(Schema::hasTable('transacciones'))->toBeTrue();
});

test('basic models can be created', function () {
    // Crear usuario
    $user = User::factory()->create();
    expect($user)->toBeInstanceOf(User::class);

    // Crear contacto
    $contacto = Contacto::create([
        'tipo' => 'cliente',
        'nombre' => 'Cliente de Prueba',
        'email' => 'cliente@test.com',
        'activo' => true,
    ]);
    expect($contacto)->toBeInstanceOf(Contacto::class);

    // Crear método de pago
    $metodoPago = MetodoPago::create([
        'nombre' => 'Efectivo',
        'activo' => true,
    ]);
    expect($metodoPago)->toBeInstanceOf(MetodoPago::class);
});
