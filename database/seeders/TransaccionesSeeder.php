<?php

namespace Database\Seeders;

use App\Models\Transaccion;
use App\Models\Contacto;
use App\Models\MetodoPago;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransaccionesSeeder extends Seeder
{
    public function run(): void
    {
        $contactos = Contacto::all();
        $metodosPago = MetodoPago::all();

        if ($contactos->isEmpty() || $metodosPago->isEmpty()) {
            $this->command->warn('No hay contactos o métodos de pago disponibles. Ejecutando seeders dependientes...');
            $this->call([
                ContactosSeeder::class,
                MetodosPagoSeeder::class,
            ]);
            $contactos = Contacto::all();
            $metodosPago = MetodoPago::all();
        }

        // Transacciones de ejemplo
        $transacciones = [
            // Ingresos
            [
                'tipo' => 'ingreso',
                'fecha' => Carbon::now()->subDays(5),
                'contacto_id' => $contactos->where('tipo', 'cliente')->first()?->id,
                'referencia_tipo' => 'obra',
                'referencia_nombre' => 'Construcción Casa Familiar - Fase 1',
                'factura_tipo' => 'manual',
                'factura_numero' => 'A-001',
                'factura_datos' => [
                    'conceptos' => [
                        [
                            'descripcion' => 'Materiales de construcción',
                            'cantidad' => 1,
                            'precio_unitario' => 50000,
                            'subtotal' => 50000,
                        ],
                        [
                            'descripcion' => 'Mano de obra especializada',
                            'cantidad' => 10,
                            'precio_unitario' => 1500,
                            'subtotal' => 15000,
                        ],
                    ],
                    'subtotal' => 65000,
                    'iva' => 10400,
                    'total' => 75400,
                ],
                'metodo_pago_id' => $metodosPago->where('nombre', 'Transferencia')->first()?->id,
                'referencia_pago' => 'TRANSF-2024-001',
                'total' => 75400,
                'observaciones' => 'Primer pago de la obra, 50% del total acordado.',
            ],
            [
                'tipo' => 'ingreso',
                'fecha' => Carbon::now()->subDays(10),
                'contacto_id' => $contactos->where('tipo', 'cliente')->skip(1)->first()?->id,
                'referencia_tipo' => 'producto',
                'referencia_nombre' => 'Venta de Material Eléctrico',
                'factura_tipo' => 'manual',
                'factura_numero' => 'B-001',
                'factura_datos' => [
                    'conceptos' => [
                        [
                            'descripcion' => 'Cable calibre 12 AWG (100m)',
                            'cantidad' => 5,
                            'precio_unitario' => 1200,
                            'subtotal' => 6000,
                        ],
                        [
                            'descripcion' => 'Contactos y apagadores',
                            'cantidad' => 20,
                            'precio_unitario' => 45,
                            'subtotal' => 900,
                        ],
                    ],
                    'subtotal' => 6900,
                    'iva' => 1104,
                    'total' => 8004,
                ],
                'metodo_pago_id' => $metodosPago->where('nombre', 'Efectivo')->first()?->id,
                'referencia_pago' => null,
                'total' => 8004,
                'observaciones' => 'Venta al contado con descuento del 5%.',
            ],

            // Egresos
            [
                'tipo' => 'egreso',
                'fecha' => Carbon::now()->subDays(3),
                'contacto_id' => $contactos->where('tipo', 'proveedor')->first()?->id,
                'referencia_tipo' => 'producto',
                'referencia_nombre' => 'Compra de Cemento y Arena',
                'factura_tipo' => 'manual',
                'factura_numero' => 'FC-2024-045',
                'factura_datos' => [
                    'conceptos' => [
                        [
                            'descripcion' => 'Cemento Portland (50 bultos)',
                            'cantidad' => 50,
                            'precio_unitario' => 185,
                            'subtotal' => 9250,
                        ],
                        [
                            'descripcion' => 'Arena fina (10 m³)',
                            'cantidad' => 10,
                            'precio_unitario' => 380,
                            'subtotal' => 3800,
                        ],
                    ],
                    'subtotal' => 13050,
                    'iva' => 2088,
                    'total' => 15138,
                ],
                'metodo_pago_id' => $metodosPago->where('nombre', 'Cheque')->first()?->id,
                'referencia_pago' => 'CHQ-001245',
                'total' => 15138,
                'observaciones' => 'Materiales para proyecto Casa Familiar.',
            ],
            [
                'tipo' => 'egreso',
                'fecha' => Carbon::now()->subDays(7),
                'contacto_id' => $contactos->where('tipo', 'proveedor')->skip(1)->first()?->id,
                'referencia_tipo' => 'servicio',
                'referencia_nombre' => 'Servicios de Transporte',
                'factura_tipo' => 'manual',
                'factura_numero' => null,
                'factura_datos' => [
                    'conceptos' => [
                        [
                            'descripcion' => 'Flete de materiales',
                            'cantidad' => 3,
                            'precio_unitario' => 800,
                            'subtotal' => 2400,
                        ],
                    ],
                    'subtotal' => 2400,
                    'iva' => 384,
                    'total' => 2784,
                ],
                'metodo_pago_id' => $metodosPago->where('nombre', 'Efectivo')->first()?->id,
                'referencia_pago' => null,
                'total' => 2784,
                'observaciones' => 'Transporte de materiales del almacén a la obra.',
            ],
            [
                'tipo' => 'egreso',
                'fecha' => Carbon::now()->subDays(15),
                'contacto_id' => null, // Sin contacto asignado
                'referencia_tipo' => 'otro',
                'referencia_nombre' => 'Gastos de Oficina',
                'factura_tipo' => 'manual',
                'factura_numero' => null,
                'factura_datos' => [
                    'conceptos' => [
                        [
                            'descripcion' => 'Papelería y suministros',
                            'cantidad' => 1,
                            'precio_unitario' => 1200,
                            'subtotal' => 1200,
                        ],
                        [
                            'descripcion' => 'Servicios de internet',
                            'cantidad' => 1,
                            'precio_unitario' => 650,
                            'subtotal' => 650,
                        ],
                    ],
                    'subtotal' => 1850,
                    'iva' => 296,
                    'total' => 2146,
                ],
                'metodo_pago_id' => $metodosPago->where('nombre', 'Tarjeta de Crédito')->first()?->id,
                'referencia_pago' => 'TDC-***1234',
                'total' => 2146,
                'observaciones' => 'Gastos administrativos del mes.',
            ],
        ];

        foreach ($transacciones as $transaccionData) {
            Transaccion::create($transaccionData);
        }

        $this->command->info('Se crearon ' . count($transacciones) . ' transacciones de ejemplo.');
    }
}
