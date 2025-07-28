<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Transaccion extends Model
{
    protected $table = 'transacciones';

    protected $fillable = [
        'folio',
        'tipo',
        'fecha',
        'contacto_id',
        'referencia_tipo',
        'referencia_nombre',
        'referencia_datos',
        'factura_tipo',
        'factura_numero',
        'factura_datos',
        'factura_archivos',
        'metodo_pago_id',
        'referencia_pago',
        'total',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'referencia_datos' => 'array',
            'factura_datos' => 'array',
            'factura_archivos' => 'array',
            'total' => 'decimal:4',
        ];
    }

    // Relaciones
    public function contacto(): BelongsTo
    {
        return $this->belongsTo(Contacto::class);
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class);
    }

    // Scopes para filtros
    public function scopeIngresos($query)
    {
        return $query->where('tipo', 'ingreso');
    }

    public function scopeEgresos($query)
    {
        return $query->where('tipo', 'egreso');
    }

    public function scopeObras($query)
    {
        return $query->where('referencia_tipo', 'obra');
    }

    public function scopeProductos($query)
    {
        return $query->where('referencia_tipo', 'producto');
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin = null)
    {
        if ($fechaFin) {
            return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        }
        return $query->whereDate('fecha', $fechaInicio);
    }

    public function scopeBuscarReferencia($query, $termino)
    {
        return $query->where('referencia_nombre', 'ILIKE', "%{$termino}%");
    }

    public function scopeBuscarFactura($query, $numero)
    {
        return $query->where('factura_numero', 'ILIKE', "%{$numero}%");
    }

    // Scope para búsquedas en conceptos de facturas (PostgreSQL JSONB)
    public function scopeBuscarEnConceptos($query, $termino)
    {
        return $query->whereRaw("
            EXISTS (
                SELECT 1 
                FROM jsonb_array_elements(factura_datos->'conceptos') as concepto
                WHERE concepto->>'descripcion' ILIKE ?
            )", ["%{$termino}%"]);
    }

    // Métodos para generar folio automático
    public static function generarFolio($tipo)
    {
        $año = date('Y');
        $prefijo = $tipo === 'ingreso' ? 'ING' : 'EGR';

        $ultimoFolio = self::where('folio', 'LIKE', "{$prefijo}-%-%{$año}")
            ->orderBy('folio', 'desc')
            ->first();

        if ($ultimoFolio) {
            // Extraer el número del folio: ING-001-2025 -> 001
            $partes = explode('-', $ultimoFolio->folio);
            $numero = intval($partes[1]) + 1;
        } else {
            $numero = 1;
        }

        return sprintf('%s-%03d-%s', $prefijo, $numero, $año);
    }

    // Métodos para manejo de archivos
    public function getArchivosUrls()
    {
        if (!$this->factura_archivos) {
            return [];
        }

        return collect($this->factura_archivos)->map(function ($ruta) {
            return [
                'ruta' => $ruta,
                'url' => Storage::url($ruta),
                'nombre' => basename($ruta),
                'existe' => Storage::exists($ruta),
            ];
        })->toArray();
    }

    // Boot method para generar folio automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaccion) {
            if (empty($transaccion->folio)) {
                $transaccion->folio = self::generarFolio($transaccion->tipo);
            }
        });
    }
}
