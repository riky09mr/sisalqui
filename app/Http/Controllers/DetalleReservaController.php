<?php

namespace App\Http\Controllers;

use App\Models\DetalleReserva;
use App\Models\Producto;
use App\Models\Reserva;
use Illuminate\Http\Request;

class DetalleReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detalles = DetalleReserva::with(['reserva', 'producto'])->get();
        return view('detalle_reserva.index', compact('detalles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reservas = Reserva::where('estado', 'activa')->get();
        $productos = Producto::activos()->get();
        return view('detalle_reserva.create', compact('reservas', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0'
        ]);

        // Verificar stock disponible
        $producto = Producto::findOrFail($request->producto_id);
        if ($producto->stock < $request->cantidad) {
            return back()->withErrors(['error' => 'Stock insuficiente']);
        }

        // Crear detalle
        $detalle = DetalleReserva::create([
            'reserva_id' => $request->reserva_id,
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
            'estado' => 'activo',
            'precio_unitario' => $request->precio_unitario
        ]);

        // Actualizar stock
        $producto->stock -= $request->cantidad;
        $producto->save();

        // Actualizar precio total de la reserva
        $reserva = Reserva::find($request->reserva_id);
        $precio_total = $reserva->detalles->sum(function($detalle) {
            return $detalle->cantidad * $detalle->precio_unitario;
        });
        $reserva->precio_total = $precio_total;
        $reserva->save();

        return redirect()->route('reservas.show', $reserva->id)
                        ->with('success', 'Detalle agregado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetalleReserva  $detalleReserva
     * @return \Illuminate\Http\Response
     */
    public function show(DetalleReserva $detalleReserva)
    {
        return view('detalle_reserva.show', compact('detalleReserva'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetalleReserva  $detalleReserva
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleReserva $detalleReserva)
    {
        $productos = Producto::activos()->get();
        return view('detalle_reserva.edit', compact('detalleReserva', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetalleReserva  $detalleReserva
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleReserva $detalleReserva)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0'
        ]);

        // Verificar stock si cambia la cantidad
        if ($request->cantidad > $detalleReserva->cantidad) {
            $diferencia = $request->cantidad - $detalleReserva->cantidad;
            $producto = Producto::findOrFail($detalleReserva->producto_id);
            if ($producto->stock < $diferencia) {
                return back()->withErrors(['error' => 'Stock insuficiente']);
            }
            // Actualizar stock
            $producto->stock -= $diferencia;
            $producto->save();
        }

        $detalleReserva->update([
            'cantidad' => $request->cantidad,
            'precio_unitario' => $request->precio_unitario,
        ]);

        // Actualizar precio total de la reserva
        $reserva = $detalleReserva->reserva;
        $precio_total = $reserva->detalles->sum(function($detalle) {
            return $detalle->cantidad * $detalle->precio_unitario;
        });
        $reserva->precio_total = $precio_total;
        $reserva->save();

        return redirect()->route('reservas.show', $detalleReserva->reserva_id)
                        ->with('success', 'Detalle actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetalleReserva  $detalleReserva
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleReserva $detalleReserva)
    {
        $reserva_id = $detalleReserva->reserva_id;

        // Devolver stock al producto
        $producto = $detalleReserva->producto;
        $producto->stock += $detalleReserva->cantidad;
        $producto->save();

        $detalleReserva->delete();

        // Actualizar precio total de la reserva
        $reserva = Reserva::find($reserva_id);
        $precio_total = $reserva->detalles->sum(function($detalle) {
            return $detalle->cantidad * $detalle->precio_unitario;
        });
        $reserva->precio_total = $precio_total;
        $reserva->save();

        return redirect()->route('reservas.show', $reserva_id)
                        ->with('success', 'Detalle eliminado exitosamente');
    }
}
