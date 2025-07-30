<?php

namespace Database\Seeders;

use App\Models\Contacto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contactos = [
            [
                'tipo' => 'cliente',
                'nombre' => 'Juan Pérez García',
                'email' => 'juan.perez@email.com',
                'telefono' => '55 1234 5678',
                'direccion' => 'Av. Insurgentes Sur 123, Col. Roma Norte, CDMX',
                'rfc' => 'PEGJ850101ABC',
                'activo' => true,
            ],
            [
                'tipo' => 'proveedor',
                'nombre' => 'Constructora ABC S.A. de C.V.',
                'email' => 'ventas@constructoraabc.com',
                'telefono' => '55 9876 5432',
                'direccion' => 'Calle Industria 456, Col. Industrial, Estado de México',
                'rfc' => 'CAB850315XYZ',
                'activo' => true,
            ],
            [
                'tipo' => 'ambos',
                'nombre' => 'María González Hernández',
                'email' => 'maria.gonzalez@empresa.com',
                'telefono' => '55 5555 1111',
                'direccion' => 'Av. Reforma 789, Col. Polanco, CDMX',
                'rfc' => 'GOHM750420DEF',
                'activo' => true,
            ],
            [
                'tipo' => 'cliente',
                'nombre' => 'Empresa Tech Solutions',
                'email' => 'contacto@techsolutions.mx',
                'telefono' => '55 2222 3333',
                'direccion' => 'Torre Corporativa, Piso 15, Santa Fe, CDMX',
                'rfc' => 'ETS120815GHI',
                'activo' => true,
            ],
            [
                'tipo' => 'proveedor',
                'nombre' => 'Ferretería El Martillo',
                'email' => 'ventas@elmartillo.com',
                'telefono' => '55 7777 8888',
                'direccion' => 'Mercado de Ferretería Local 25, Col. Centro, CDMX',
                'rfc' => 'FEM950210JKL',
                'activo' => true,
            ],
            [
                'tipo' => 'cliente',
                'nombre' => 'Restaurant Los Compadres',
                'email' => 'admin@loscompadres.mx',
                'telefono' => '55 4444 6666',
                'direccion' => 'Av. Universidad 321, Col. Del Valle, CDMX',
                'rfc' => 'RLC030505MNO',
                'activo' => false,
            ],
            [
                'tipo' => 'proveedor',
                'nombre' => 'Distribuidora Industrial del Norte',
                'email' => 'compras@dinorte.com',
                'telefono' => '81 1111 2222',
                'direccion' => 'Zona Industrial, Monterrey, Nuevo León',
                'rfc' => 'DIN880725PQR',
                'activo' => true,
            ],
            [
                'tipo' => 'ambos',
                'nombre' => 'Carlos Rodríguez Martínez',
                'email' => 'carlos.rodriguez@email.com',
                'telefono' => '55 3333 4444',
                'direccion' => 'Calle Maple 159, Col. Americana, Guadalajara, Jalisco',
                'rfc' => 'ROMC820912STU',
                'activo' => true,
            ]
        ];

        foreach ($contactos as $contacto) {
            Contacto::create($contacto);
        }
    }
}
