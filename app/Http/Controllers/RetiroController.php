<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetiroController extends Controller
{
    public function index()
    {
        $reservasPorRetirar = Reserva::where('estado', 4) // Entregadas pendientes de retiro
            ->with(['cliente', 'detalles.producto'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $reservasRetiradas = Reserva::where('estado', 5) // Retiradas
            ->whereDate('updated_at', today())
            ->with(['cliente', 'detalles.producto'])
            ->get();

        return view('retiros.index', compact('reservasPorRetirar', 'reservasRetiradas'));
    }

    public function retirar(Request $request, Reserva $reserva)
    {
        try {
            DB::beginTransaction();

            if ($reserva->estado !== 4) {
                throw new \Exception('La reserva debe estar entregada para poder marcarla como retirada');
            }

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

    public function show(Reserva $reserva)
    {
        // Verificar que la reserva esté en estado entregado (4)
        if ($reserva->estado !== 4) {
            return redirect()->route('retiros.index')
                ->with('error', 'Solo se pueden procesar retiros de reservas entregadas');
        }

        $reserva->load(['cliente', 'detalles.producto']);
        return view('retiros.show', compact('reserva'));
    }

    public function procesarRetiro(Request $request, Reserva $reserva)
    {
        try {
            DB::beginTransaction();

            if ($reserva->estado !== 4) {
                throw new \Exception('La reserva debe estar entregada para poder procesarla');
            }

            $detallesRetirados = $request->detalles;
            $todoCorrecto = true;

            foreach ($detallesRetirados as $detalleId => $datos) {
                $detalle = $reserva->detalles()->findOrFail($detalleId);

                if (isset($datos['retiro_completo']) && $datos['retiro_completo']) {
                    // Si se marcó como retiro completo
                    $cantidadRetirada = $detalle->cantidad;
                } else {
                    // Si se especificó una cantidad
                    $cantidadRetirada = (int)$datos['cantidad_retirada'];

                    if ($cantidadRetirada < $detalle->cantidad) {
                        $todoCorrecto = false;
                        // Solo si no se retira todo, reducimos el stock general permanentemente
                        // porque significa que se perdió o dañó el producto
                        $diferencia = $detalle->cantidad - $cantidadRetirada;
                        $detalle->producto->decrement('stock', $diferencia);
                    }
                }

                $detalle->update([
                    'cantidad_retirada' => $cantidadRetirada,
                    'retiro_completo' => isset($datos['retiro_completo']) && $datos['retiro_completo'],
                    'fecha_retiro' => now()
                ]);
            }

            // Actualizar estado de la reserva
            $reserva->update([
                'estado' => 5, // Retirado
                'retiro_completo' => $todoCorrecto,
                'fecha_retiro' => now()
            ]);

            DB::commit();

            return redirect()->route('retiros.index')
                ->with('success', 'Retiro procesado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Error al procesar el retiro: ' . $e->getMessage())
                ->withInput();
        }
    }
}
