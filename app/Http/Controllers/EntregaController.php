<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EntregaController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $request->get('fecha', date('Y-m-d'));
        $search = $request->get('search');

        $reservasConfirmadas = Reserva::where('estado', 2)
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('cliente', function ($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%")
                      ->orWhere('apellido', 'LIKE', "%{$search}%");
                });
            })
            ->when($fecha, function ($query) use ($fecha) {
                return $query->whereDate('fecha_entrega', $fecha);
            })
            ->with(['cliente', 'detalles.producto'])
            ->orderBy('fecha_entrega')
            ->get();

        $reservasEntregadas = Reserva::where('estado', 4)
            ->whereDate('updated_at', today())
            ->with(['cliente', 'detalles.producto'])
            ->get();

        return view('entregas.index', compact('reservasConfirmadas', 'reservasEntregadas'));
    }

    public function exportarPdf(Request $request)
    {
        $fecha = $request->get('fecha', date('Y-m-d'));

        $reservas = Reserva::where('estado', 2)
            ->whereDate('fecha_entrega', $fecha)
            ->with(['cliente', 'detalles.producto'])
            ->orderBy('fecha_entrega')
            ->get();

        $pdf = PDF::loadView('entregas.pdf', [
            'reservas' => $reservas,
            'fecha' => Carbon::parse($fecha)->format('d/m/Y')
        ]);

        return $pdf->download('entregas-' . $fecha . '.pdf');
    }

    public function verPdf(Request $request)
    {
        $fecha = $request->get('fecha', date('Y-m-d'));

        $reservas = Reserva::where('estado', 2)
            ->whereDate('fecha_entrega', $fecha)
            ->with(['cliente', 'detalles.producto'])
            ->orderBy('fecha_entrega')
            ->get();

        $pdf = PDF::loadView('entregas.pdf', [
            'reservas' => $reservas,
            'fecha' => Carbon::parse($fecha)->format('d/m/Y')
        ]);

        return $pdf->stream('entregas-' . $fecha . '.pdf');
    }

    public function entregar(Request $request, Reserva $reserva)
    {
        try {
            DB::beginTransaction();

            // Validar que la reserva esté confirmada
            if ($reserva->estado !== 2) {
                throw new \Exception('La reserva debe estar confirmada para poder entregarla');
            }

            // Actualizar estado de la reserva
            $reserva->update([
                'estado' => 4, // Entregado
                'fecha_entrega_real' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reserva entregada exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al entregar la reserva: ' . $e->getMessage()
            ], 500);
        }
    }

    public function retirar(Request $request, Reserva $reserva)
    {
        try {
            DB::beginTransaction();

            // Validar que la reserva esté entregada
            if ($reserva->estado !== 4) {
                throw new \Exception('La reserva debe estar entregada para poder marcarla como retirada');
            }

            // Actualizar estado de la reserva
            $reserva->update([
                'estado' => 5, // Retirado
                'fecha_retiro' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reserva marcada como retirada exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar la reserva como retirada: ' . $e->getMessage()
            ], 500);
        }
    }
}
