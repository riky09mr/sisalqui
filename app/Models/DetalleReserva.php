<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleReserva extends Model
{
    protected $table = 'detalle_reservas';

    protected $fillable = [
        'reserva_id',
        'producto_id',
        'cantidad',
        'estado',
        'precio_unitario'
    ];

    // Relación con Reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
