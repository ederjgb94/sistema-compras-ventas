<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Saldo del día actual
        $hoy = now()->toDateString();
        $saldoHoy = $this->calcularSaldoPorFecha($hoy);

        // Saldo de la semana actual
        $inicioSemana = now()->startOfWeek()->toDateString();
        $finSemana = now()->endOfWeek()->toDateString();
        $saldoSemana = $this->calcularSaldoPorPeriodo($inicioSemana, $finSemana);

        // Saldo del mes actual
        $inicioMes = now()->startOfMonth()->toDateString();
        $finMes = now()->endOfMonth()->toDateString();
        $saldoMes = $this->calcularSaldoPorPeriodo($inicioMes, $finMes);

        // Últimas 10 transacciones
        $ultimasTransacciones = Transaccion::with(['contacto', 'metodoPago'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'saldoHoy',
            'saldoSemana',
            'saldoMes',
            'ultimasTransacciones'
        ));
    }

    private function calcularSaldoPorFecha($fecha)
    {
        $resultado = Transaccion::selectRaw("
            SUM(CASE WHEN tipo = 'ingreso' THEN total ELSE 0 END) as ingresos,
            SUM(CASE WHEN tipo = 'egreso' THEN total ELSE 0 END) as egresos,
            SUM(CASE WHEN tipo = 'ingreso' THEN total ELSE -total END) as saldo
        ")->whereDate('fecha', $fecha)->first();

        // Asegurar que siempre devolvemos un objeto con las propiedades esperadas
        return (object) [
            'ingresos' => $resultado->ingresos ?? 0,
            'egresos' => $resultado->egresos ?? 0,
            'saldo' => $resultado->saldo ?? 0,
        ];
    }

    private function calcularSaldoPorPeriodo($fechaInicio, $fechaFin)
    {
        $resultado = Transaccion::selectRaw("
            SUM(CASE WHEN tipo = 'ingreso' THEN total ELSE 0 END) as ingresos,
            SUM(CASE WHEN tipo = 'egreso' THEN total ELSE 0 END) as egresos,
            SUM(CASE WHEN tipo = 'ingreso' THEN total ELSE -total END) as saldo
        ")->whereBetween('fecha', [$fechaInicio, $fechaFin])->first();

        // Asegurar que siempre devolvemos un objeto con las propiedades esperadas
        return (object) [
            'ingresos' => $resultado->ingresos ?? 0,
            'egresos' => $resultado->egresos ?? 0,
            'saldo' => $resultado->saldo ?? 0,
        ];
    }
}
