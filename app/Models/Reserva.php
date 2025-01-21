<?php

namespace App\Models;

use App\Models\DetalleReserva;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'cliente_id',
        'fecha_reserva',
        'fecha_entrega',
        'direccion',
        'descripcion',
        'precio_total',
        'estado'  // 1=pendiente, 2=confirmado, 3=cancelado, 4=entregado, 5=retirado
    ];

    protected $dates = [
        'fecha_entrega',
        'fecha_reserva',
        'created_at',
        'updated_at'
    ];

    // Constantes para los estados
    const ESTADO_PENDIENTE = 1;
    const ESTADO_CONFIRMADO = 2;
    const ESTADO_CANCELADO = 3;
    const ESTADO_ENTREGADO = 4;
    const ESTADO_RETIRADO = 5;

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con DetalleReserva
    public function detalles()
    {
        return $this->hasMany(DetalleReserva::class);
    }

    // Scope para reservas activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    // Mutador para asegurar formato de fechas
    public function setFechaReservaAttribute($value)
    {
        $this->attributes['fecha_reserva'] = date('Y-m-d', strtotime($value));
    }

    public function setFechaEntregaAttribute($value)
    {
        $this->attributes['fecha_entrega'] = date('Y-m-d', strtotime($value));
    }

    // Accessor para formato de fechas en vista
    public function getFechaReservaFormateadaAttribute()
    {
        return date('d/m/Y', strtotime($this->fecha_reserva));
    }

    public function getFechaEntregaFormateadaAttribute()
    {
        return date('d/m/Y', strtotime($this->fecha_entrega));
    }

    // Método para obtener el nombre del estado
    public function getNombreEstadoAttribute()
    {
        switch ((int)$this->estado) {
            case 1:
                return 'Pendiente';
            case 2:
                return 'Confirmado';
            case 3:
                return 'Cancelado';
            case 4:
                return 'Entregado';
            case 5:
                return 'Retirado';
            default:
                return 'Desconocido';
        }
    }

    // Método para obtener la clase del badge
    public function getEstadoBadgeClassAttribute()
    {
        switch ((int)$this->estado) {
            case 1:
                return 'warning';
            case 2:
                return 'info';
            case 3:
                return 'danger';
            case 4:
                return 'success';
            case 5:
                return 'secondary';
            default:
                return 'light';
        }
    }
}
