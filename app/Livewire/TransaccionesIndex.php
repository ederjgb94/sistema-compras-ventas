<?php

namespace App\Livewire;

use App\Models\Transaccion;
use App\Models\Contacto;
use App\Models\MetodoPago;
use Livewire\Component;
use Livewire\WithPagination;

class TransaccionesIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $tipo_filter = '';
    public $metodo_pago_filter = '';
    public $fecha_desde = '';
    public $fecha_hasta = '';
    public $sort = 'fecha';
    public $direction = 'desc';

    public function mount()
    {
        $this->search = request('search', '');
        $this->tipo_filter = request('tipoFiltro', '');
        $this->metodo_pago_filter = request('metodo_pago', '');
        $this->fecha_desde = request('fecha_desde', '');
        $this->fecha_hasta = request('fecha_hasta', '');
        $this->sort = request('sort', 'fecha');
        $this->direction = request('direction', 'desc');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTipoFilter()
    {
        $this->resetPage();
    }

    public function updatedMetodoPagoFilter()
    {
        $this->resetPage();
    }

    public function updatedFechaDesde()
    {
        $this->resetPage();
    }

    public function updatedFechaHasta()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sort === $field) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $field;
            $this->direction = 'asc';
        }

        return redirect()->to(
            request()->url() . '?' . http_build_query([
                'search' => $this->search,
                'tipoFiltro' => $this->tipo_filter,
                'metodo_pago' => $this->metodo_pago_filter,
                'fecha_desde' => $this->fecha_desde,
                'fecha_hasta' => $this->fecha_hasta,
                'sort' => $this->sort,
                'direction' => $this->direction,
            ])
        );
    }

    public function render()
    {
        $query = Transaccion::with(['contacto', 'metodoPago']);

        // Aplicar filtros
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('folio', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('referencia_nombre', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('factura_numero', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('observaciones', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('referencia_pago', 'LIKE', '%' . $this->search . '%')
                    ->orWhereHas('contacto', function ($contactoQuery) {
                        $contactoQuery->where('nombre', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('email', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('telefono', 'LIKE', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->tipo_filter) {
            $query->where('tipo', $this->tipo_filter);
        }

        if ($this->metodo_pago_filter) {
            $query->where('metodo_pago_id', $this->metodo_pago_filter);
        }

        if ($this->fecha_desde) {
            $query->whereDate('fecha', '>=', $this->fecha_desde);
        }

        if ($this->fecha_hasta) {
            $query->whereDate('fecha', '<=', $this->fecha_hasta);
        }

        // Aplicar ordenamiento
        $query->orderBy($this->sort, $this->direction);

        $transacciones = $query->paginate(10);

        // Append query parameters to pagination links
        $transacciones->appends([
            'search' => $this->search,
            'tipoFiltro' => $this->tipo_filter,
            'metodo_pago' => $this->metodo_pago_filter,
            'fecha_desde' => $this->fecha_desde,
            'fecha_hasta' => $this->fecha_hasta,
            'sort' => $this->sort,
            'direction' => $this->direction,
        ]);

        // Calcular estadísticas
        $totalIngresos = Transaccion::where('tipo', 'ingreso')->sum('total');
        $totalEgresos = Transaccion::where('tipo', 'egreso')->sum('total');
        $balanceTotal = $totalIngresos - $totalEgresos;

        // Obtener métodos de pago para el filtro
        $metodosPago = MetodoPago::all();

        return view('livewire.transacciones-index', compact(
            'transacciones',
            'totalIngresos',
            'totalEgresos',
            'balanceTotal',
            'metodosPago'
        ));
    }
}
