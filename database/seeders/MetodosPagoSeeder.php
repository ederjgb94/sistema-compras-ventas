<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodosPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodos = [
            ['nombre' => 'Efectivo', 'activo' => true],
            ['nombre' => 'Transferencia', 'activo' => true],
            ['nombre' => 'Tarjeta de Crédito', 'activo' => true],
            ['nombre' => 'Cheque', 'activo' => true],
        ];

        foreach ($metodos as $metodo) {
            \App\Models\MetodoPago::updateOrCreate(
                ['nombre' => $metodo['nombre']],
                $metodo
            );
        }
    }
}
