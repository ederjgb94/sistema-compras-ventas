<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacto extends Model
{
    use SoftDeletes; // Habilita soft deletes

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
            $q->where('nombre', 'LIKE', "%{$termino}%")
                ->orWhere('rfc', 'LIKE', "%{$termino}%")
                ->orWhere('email', 'LIKE', "%{$termino}%");
        });
    }

    // Métodos específicos para soft deletes
    public function scopeConTransacciones($query)
    {
        return $query->whereHas('transacciones');
    }

    public function scopeSinTransacciones($query)
    {
        return $query->whereDoesntHave('transacciones');
    }

    public function puedeSerEliminado(): bool
    {
        return $this->transacciones()->count() === 0;
    }

    public function tieneTransacciones(): bool
    {
        return $this->transacciones()->count() > 0;
    }

    public function cantidadTransacciones(): int
    {
        return $this->transacciones()->count();
    }
}
