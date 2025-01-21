<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'costo_compra',
        'precio_alquiler',
        'stock',
        'imagen',
        'categoria',
        'stock_minimo',
        'estado'
    ];

    protected $casts = [
        'costo_compra' => 'decimal:2',
        'precio_alquiler' => 'decimal:2',
    ];

    // Relación con DetalleReserva
    public function detallesReserva()
    {
        return $this->hasMany(DetalleReserva::class);
    }

    // Scope para productos activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }

    // Accessor para formato de precio
    public function getPrecioFormateadoAttribute()
    {
        return number_format($this->precio, 2);
    }
}
