<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Obtener fechas
        $hoy = Carbon::today();
        $manana = Carbon::tomorrow();
        $pasadoManana = Carbon::tomorrow()->addDay();

        // Obtener todas las reservas confirmadas
        $todasLasReservas = Reserva::where('estado', 2)
            ->with('cliente')
            ->orderBy('fecha_entrega')
            ->get();

        // Agrupar por fecha de entrega
        $reservasHoy = $todasLasReservas->filter(function($reserva) use ($hoy) {
            return Carbon::parse($reserva->fecha_entrega)->isSameDay($hoy);
        });

        $reservasManana = $todasLasReservas->filter(function($reserva) use ($manana) {
            return Carbon::parse($reserva->fecha_entrega)->isSameDay($manana);
        });

        $reservasPasadoManana = $todasLasReservas->filter(function($reserva) use ($pasadoManana) {
            return Carbon::parse($reserva->fecha_entrega)->isSameDay($pasadoManana);
        });

        // Reservas futuras (más allá de pasado mañana)
        $reservasFuturas = $todasLasReservas->filter(function($reserva) use ($pasadoManana) {
            return Carbon::parse($reserva->fecha_entrega)->isAfter($pasadoManana);
        });

        // Pedidos por retirar
        $pedidosPorRetirar = Reserva::where('estado', 4)
            ->with('cliente')
            ->get();

        // Pedidos entregados hoy
        $pedidosEntregadosHoy = Reserva::whereDate('updated_at', $hoy)
            ->where('estado', 4)
            ->with('cliente')
            ->get();

        // Debug
        \Log::info('Reservas agrupadas:', [
            'hoy' => $reservasHoy->count(),
            'manana' => $reservasManana->count(),
            'pasado_manana' => $reservasPasadoManana->count(),
            'futuras' => $reservasFuturas->count()
        ]);

        return view('home', compact(
            'reservasHoy',
            'reservasManana',
            'reservasPasadoManana',
            'reservasFuturas',
            'pedidosPorRetirar',
            'pedidosEntregadosHoy'
        ));
    }
}
