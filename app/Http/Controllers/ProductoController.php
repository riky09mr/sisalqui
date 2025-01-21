<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DetalleReserva;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        // Filtro por nombre
        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', '%' . $request->nombre . '%');
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $productos = $query->orderBy('id', 'desc')->paginate(10);

        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'costo_compra' => 'required|numeric|min:0',
                'precio_alquiler' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categoria' => 'required|integer',
                'stock_minimo' => 'required|integer|min:0',
                'estado' => 'required|integer'
            ]);

            // Manejo de la imagen
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $imagen->storeAs('public/productos', $nombreImagen);
                $rutaImagen = 'productos/' . $nombreImagen;
            }

            $producto = Producto::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'costo_compra' => $request->costo_compra,
                'precio_alquiler' => $request->precio_alquiler,
                'stock' => $request->stock,
                'imagen' => $rutaImagen ?? null,
                'categoria' => $request->categoria,
                'stock_minimo' => $request->stock_minimo,
                'estado' => $request->estado
            ]);

            return redirect()->route('productos.index')
                ->with('success', 'Producto creado exitosamente');
        } catch (\Exception $e) {
            dd($e->getMessage()); // Esto mostrará el error
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'costo_compra' => 'required|numeric|min:0',
            'precio_alquiler' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoria' => 'required|integer',
            'stock_minimo' => 'required|integer|min:0',
            'estado' => 'required|integer'
        ]);

        // Manejo de la imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::delete('public/' . $producto->imagen);
            }

            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->storeAs('public/productos', $nombreImagen);
            $rutaImagen = 'productos/' . $nombreImagen;
        }

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'costo_compra' => $request->costo_compra,
            'precio_alquiler' => $request->precio_alquiler,
            'stock' => $request->stock,
            'imagen' => $rutaImagen ?? $producto->imagen,
            'categoria' => $request->categoria,
            'stock_minimo' => $request->stock_minimo,
            'estado' => $request->estado
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->update(['estado' => 0]);
        return redirect()->route('productos.index')
            ->with('success', 'Producto desactivado exitosamente');
    }

    public function search(Request $request)
    {
        $fecha = $request->get('fecha');
        if (!$fecha) {
            return response()->json(['error' => 'La fecha es requerida'], 400);
        }

        $productos = Producto::where(function ($query) use ($request) {
            $term = $request->get('q');
            $query->where('nombre', 'LIKE', "%{$term}%")
                  ->orWhere('descripcion', 'LIKE', "%{$term}%");
        })
        ->where('estado', 1)
        ->get()
        ->map(function ($producto) use ($fecha) {
            $disponibilidad = $this->checkDisponibilidad($producto->id, $fecha, 1);

            return [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio_alquiler' => $producto->precio_alquiler,
                'stock_total' => $producto->stock,
                'stock_disponible' => $disponibilidad['stock_disponible'],
                'text' => $producto->nombre . ' (Disponibles: ' . $disponibilidad['stock_disponible'] . ')'
            ];
        });

        return response()->json($productos);
    }

    public function checkDisponibilidad($producto_id, $fecha, $cantidad_solicitada)
    {
        $producto = Producto::findOrFail($producto_id);
        $stock_total = $producto->stock; // Este nunca cambia

        // Solo verificamos cuántas reservas hay para ese día específico
        $cantidad_reservada = DetalleReserva::whereHas('reserva', function ($query) use ($fecha) {
            $query->whereDate('fecha_reserva', $fecha)
                  ->whereIn('estado', [2, 4]); // Solo confirmadas o entregadas
        })
        ->where('producto_id', $producto_id)
        ->where('estado', 1)
        ->sum('cantidad');

        $disponible_para_fecha = $stock_total - $cantidad_reservada;

        return [
            'disponible' => $disponible_para_fecha >= $cantidad_solicitada,
            'stock_disponible' => $disponible_para_fecha,
            'stock_total' => $stock_total
        ];
    }
}
