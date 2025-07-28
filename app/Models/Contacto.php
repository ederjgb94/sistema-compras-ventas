<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contacto extends Model
{
    protected $fillable = [
        'tipo',
        'nombre',
        'email',
        'telefono',
        'direccion',
        'rfc',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    // Relaciones
    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeClientes($query)
    {
        return $query->whereIn('tipo', ['cliente', 'ambos']);
    }

    public function scopeProveedores($query)
    {
        return $query->whereIn('tipo', ['proveedor', 'ambos']);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('nombre', 'ILIKE', "%{$termino}%")
                ->orWhere('rfc', 'ILIKE', "%{$termino}%")
                ->orWhere('email', 'ILIKE', "%{$termino}%");
        });
    }
}
