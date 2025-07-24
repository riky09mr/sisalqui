<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\DetalleReserva;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Reserva::query()->with('cliente');

        // Búsqueda por nombre o cédula del cliente
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('cliente', function ($q) use ($searchTerm) {
                $q->where('nombre', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('cedula', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $reservas = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('reservas.index', compact('reservas'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('reservas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            \Log::info('Intentando crear reserva con datos:', $request->all());

            // Validación
            $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'fecha_reserva' => 'required|date',
                'fecha_entrega' => 'required|date|before_or_equal:fecha_reserva',
                'direccion_entrega' => 'required|string',
                'productos' => 'required|array',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio' => 'required|numeric|min:0'
            ], [
                'cliente_id.required' => 'El cliente es obligatorio.',
                'cliente_id.exists' => 'El cliente seleccionado no existe.',
                'fecha_reserva.required' => 'La fecha del evento es obligatoria.',
                'fecha_reserva.date' => 'La fecha del evento no es válida.',
                'fecha_entrega.required' => 'La fecha de entrega es obligatoria.',
                'fecha_entrega.date' => 'La fecha de entrega no es válida.',
                'fecha_entrega.before_or_equal' => 'La fecha de entrega no puede ser posterior a la fecha del evento.',
                'direccion_entrega.required' => 'La dirección de entrega es obligatoria.',
                'productos.required' => 'Debe agregar al menos un producto.',
                'productos.array' => 'El formato de productos no es válido.',
                'productos.*.id.required' => 'El producto es obligatorio.',
                'productos.*.id.exists' => 'El producto seleccionado no existe.',
                'productos.*.cantidad.required' => 'La cantidad es obligatoria.',
                'productos.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
                'productos.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
                'productos.*.precio.required' => 'El precio es obligatorio.',
                'productos.*.precio.numeric' => 'El precio debe ser un número.',
                'productos.*.precio.min' => 'El precio debe ser mayor o igual a 0.'
            ]);

            // Calcular precio total
            $precioTotal = $this->calcularTotal($request->productos);

            // Crear la reserva
            $reserva = Reserva::create([
                'cliente_id' => $request->cliente_id,
                'fecha_reserva' => $request->fecha_reserva,
                'fecha_entrega' => $request->fecha_entrega,
                'direccion_entrega' => $request->direccion_entrega,
                'precio_total' => $precioTotal,
                'total' => $precioTotal, // <--- AGREGAR ESTA LÍNEA
                'estado' => 1
            ]);

            foreach($request->productos as $producto) {
                // Verificar disponibilidad para la fecha
                $disponibilidad = app(ProductoController::class)
                    ->checkDisponibilidad($producto['id'], $request->fecha_reserva, $producto['cantidad']);

                if (!$disponibilidad['disponible']) {
                    throw new \Exception("Producto no disponible para la fecha seleccionada");
                }

                // Crear detalle sin modificar el stock
                DetalleReserva::create([
                    'reserva_id' => $reserva->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio'],
                    'estado' => 1
                ]);
            }

            DB::commit();

            \Log::info('Reserva creada exitosamente:', [
                'id' => $reserva->id,
                'precio_total' => $precioTotal,
                'productos' => $request->productos
            ]);

            return redirect()->route('reservas.show', $reserva)
                ->with('success', 'Reserva creada exitosamente.');

        } catch (ValidationException $e) {
            DB::rollBack();
            \Log::error('Error de validación:', [
                'errores' => $e->errors()
            ]);
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear la reserva:', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }

    private function calcularTotal($productos)
    {
        return collect($productos)->sum(function($item) {
            return $item['precio'] * $item['cantidad'];
        });
    }

    public function show(Reserva $reserva)
    {
        return view('reservas.show', compact('reserva'));
    }

    public function edit(Reserva $reserva)
    {
        if ($reserva->estado !== 1) {
            return redirect()->route('reservas.show', $reserva)
                ->with('error', 'Solo se pueden editar reservas en estado pendiente');
        }

        $clientes = Cliente::all();
        $productos = Producto::where('estado', 1)->get();
        $detalles = $reserva->detalles()->with('producto')->get();

        // Agregar un dd temporal para verificar que hay productos
        // dd($productos);

        return view('reservas.edit', compact('reserva', 'clientes', 'productos', 'detalles'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        try {
            DB::beginTransaction();

            // Validar los datos básicos
            $request->validate([
                'cliente_id' => 'required',
                'direccion_entrega' => 'required',
                'fecha_reserva' => 'required|date',
                'fecha_entrega' => 'required|date',
            ]);

            // Actualizar la reserva
            $reserva->update([
                'cliente_id' => $request->cliente_id,
                'direccion_entrega' => $request->direccion_entrega,
                'fecha_reserva' => $request->fecha_reserva,
                'fecha_entrega' => $request->fecha_entrega,
                'precio_total' => $this->calcularTotal($request->productos),
                'total' => $this->calcularTotal($request->productos) // <--- AGREGAR ESTA LÍNEA
            ]);

            // Desactivar detalles anteriores
            $reserva->detalles()->update(['estado' => 0]);

            foreach($request->productos as $producto) {
                // Verificar disponibilidad para la fecha
                $disponibilidad = app(ProductoController::class)
                    ->checkDisponibilidad($producto['id'], $request->fecha_reserva, $producto['cantidad']);

                if (!$disponibilidad['disponible']) {
                    throw new \Exception("No hay suficiente disponibilidad del producto para la fecha seleccionada");
                }

                // Crear nuevo detalle sin modificar el stock físico
                DetalleReserva::create([
                    'reserva_id' => $reserva->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio'],
                    'estado' => 1
                ]);
            }

            DB::commit();
            return redirect()->route('reservas.show', $reserva->id)
                            ->with('success', 'Reserva actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Error al actualizar la reserva: ' . $e->getMessage());
        }
    }

    public function destroy(Reserva $reserva)
    {
        try {
            $reserva->delete();
            return redirect()->route('reservas.index')->with('success', 'Reserva eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la reserva: ' . $e->getMessage());
        }
    }

    public function confirmar(Reserva $reserva)
    {
        try {
            DB::beginTransaction();

            $reserva->update([
                'estado' => Reserva::ESTADO_CONFIRMADO
            ]);

            DB::commit();
            return redirect()->route('reservas.index')
                ->with('success', 'La reserva ha sido confirmada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('reservas.index')
                ->with('error', 'Error al confirmar la reserva: ' . $e->getMessage());
        }
    }

    public function cancelar(Reserva $reserva)
    {
        try {
            DB::beginTransaction();

            if (!in_array($reserva->estado, [1, 2])) { // Solo pendientes o confirmadas
                throw new \Exception('Solo se pueden cancelar reservas pendientes o confirmadas');
            }

            // Actualizar estado de la reserva
            $reserva->update([
                'estado' => 3, // Cancelada
                'fecha_cancelacion' => now()
            ]);

            // Marcar detalles como cancelados
            $reserva->detalles()->update([
                'estado' => 0 // Inactivo/Cancelado
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reserva cancelada exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la reserva: ' . $e->getMessage()
            ], 500);
        }
    }

    public function eliminarDetalle(Request $request, Reserva $reserva, DetalleReserva $detalle)
    {
        try {
            DB::beginTransaction();

            // Verificar que el detalle pertenece a la reserva
            if ($detalle->reserva_id !== $reserva->id) {
                throw new \Exception('El detalle no pertenece a esta reserva');
            }

            // Devolver el stock al producto
            $producto = Producto::find($detalle->producto_id);
            $producto->increment('stock', $detalle->cantidad);

            // Marcar como eliminado
            $detalle->update(['estado' => 0]);

            // Recalcular el precio total de la reserva
            $nuevoPrecioTotal = $reserva->detalles()
                ->where('estado', 1)
                ->sum(DB::raw('cantidad * precio_unitario'));

            $reserva->update(['precio_total' => $nuevoPrecioTotal]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente',
                'nuevo_precio_total' => $nuevoPrecioTotal
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verPdf(Reserva $reserva)
    {
        $pdf = PDF::loadView('reservas.reservas-pdf', compact('reserva'));
        return $pdf->stream('reserva-' . $reserva->id . '.pdf');
    }
}
