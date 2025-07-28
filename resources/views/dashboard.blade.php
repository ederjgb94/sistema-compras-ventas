<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Compras-Ventas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-800">🏢 Sistema Compras-Ventas</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Saldos Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Saldo Hoy -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-2xl">💰</div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Saldo Hoy
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    ${{ number_format($saldoHoy->saldo ?? 0, 2) }}
                                </dd>
                                <dd class="text-sm text-gray-500">
                                    Ingresos: ${{ number_format($saldoHoy->ingresos ?? 0, 2) }} | 
                                    Egresos: ${{ number_format($saldoHoy->egresos ?? 0, 2) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Saldo Semana -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-2xl">📅</div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Saldo Esta Semana
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    ${{ number_format($saldoSemana->saldo ?? 0, 2) }}
                                </dd>
                                <dd class="text-sm text-gray-500">
                                    Ingresos: ${{ number_format($saldoSemana->ingresos ?? 0, 2) }} | 
                                    Egresos: ${{ number_format($saldoSemana->egresos ?? 0, 2) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Saldo Mes -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="text-2xl">📆</div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Saldo Este Mes
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    ${{ number_format($saldoMes->saldo ?? 0, 2) }}
                                </dd>
                                <dd class="text-sm text-gray-500">
                                    Ingresos: ${{ number_format($saldoMes->ingresos ?? 0, 2) }} | 
                                    Egresos: ${{ number_format($saldoMes->egresos ?? 0, 2) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimas Transacciones -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Últimas Transacciones
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Las 10 transacciones más recientes
                </p>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($ultimasTransacciones as $transaccion)
                <li class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($transaccion->tipo === 'ingreso')
                                    <div class="text-green-500 text-xl">↗️</div>
                                @else
                                    <div class="text-red-500 text-xl">↙️</div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $transaccion->folio }} - {{ $transaccion->referencia_nombre }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $transaccion->contacto->nombre ?? 'Sin contacto' }} | 
                                    {{ $transaccion->metodoPago->nombre ?? 'Sin método' }} |
                                    {{ $transaccion->fecha->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="text-sm font-medium {{ $transaccion->tipo === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaccion->tipo === 'ingreso' ? '+' : '-' }}${{ number_format($transaccion->total, 2) }}
                        </div>
                    </div>
                </li>
                @empty
                <li class="px-4 py-4 sm:px-6 text-center text-gray-500">
                    No hay transacciones registradas
                </li>
                @endforelse
            </ul>
        </div>

        <!-- Acciones Rápidas -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200">
                ➕ Nueva Transacción
            </button>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200">
                👥 Gestionar Contactos
            </button>
            <button class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200">
                📊 Reportes
            </button>
            <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200">
                ⚙️ Configuración
            </button>
        </div>
    </div>
</body>
</html>
