<?php

namespace App\Constants;

class SystemConstants
{
    // Estados de Reserva
    const ESTADOS_RESERVA = [
        1 => 'Pendiente',
        2 => 'Confirmado',
        3 => 'Entregado',
        4 => 'Devuelto',
        5 => 'Cancelado'
    ];

    const ESTADOS_RESERVA_COLORES = [
        1 => 'warning',   // Pendiente
        2 => 'info',      // Confirmado
        3 => 'primary',   // Entregado
        4 => 'success',   // Devuelto
        5 => 'danger'     // Cancelado
    ];

    // Estados de Producto
    const ESTADOS_PRODUCTO = [
        1 => 'Activo',
        2 => 'Inactivo'
    ];

    const ESTADOS_PRODUCTO_COLORES = [
        1 => 'success',    // Activo
        2 => 'secondary'   // Inactivo
    ];

    // Categorías de Producto
    const CATEGORIAS_PRODUCTO = [
        1 => 'Mesas',
        2 => 'Sillas',
        3 => 'Manteles',
        4 => 'Carpas',
        5 => 'Vajilla',
        6 => 'Equipos'
    ];

    public static function getEstadoReserva($estado)
    {
        return self::ESTADOS_RESERVA[$estado] ?? 'Desconocido';
    }

    public static function getColorEstadoReserva($estado)
    {
        return self::ESTADOS_RESERVA_COLORES[$estado] ?? 'light';
    }

    public static function getEstadoProducto($estado)
    {
        return self::ESTADOS_PRODUCTO[$estado] ?? 'Desconocido';
    }

    public static function getColorEstadoProducto($estado)
    {
        return self::ESTADOS_PRODUCTO_COLORES[$estado] ?? 'light';
    }

    public static function getCategoriaProducto($categoria)
    {
        return self::CATEGORIAS_PRODUCTO[$categoria] ?? 'Desconocido';
    }
}
