@extends('layouts.app')

@php
    use App\Constants\SystemConstants;
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Producto #{{ $producto->id }}</h3>
                        <div>
                            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-light mr-2">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('productos.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Información General -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="bg-light p-3 rounded">
                                <h5 class="border-bottom pb-2">Información General</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nombre:</strong></p>
                                        <p class="text-primary">{{ $producto->nombre }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Categoría:</strong></p>
                                        <p class="text-primary">{{ $SystemConstants::getCategoriaProducto($producto->categoria) }}</p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <p class="mb-1"><strong>Descripción:</strong></p>
                                        <p class="text-primary">{{ $producto->descripcion }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Precios -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="bg-light p-3 rounded">
                                <h5 class="border-bottom pb-2">Información de Precios</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Costo de Compra:</strong></p>
                                        <p class="text-primary">${{ number_format($producto->costo_compra, 2) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Precio de Alquiler:</strong></p>
                                        <p class="text-primary">${{ number_format($producto->precio_alquiler, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Stock -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="bg-light p-3 rounded">
                                <h5 class="border-bottom pb-2">Información de Stock</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Stock Actual:</strong></p>
                                        <p class="text-primary">{{ $producto->stock }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Stock Mínimo:</strong></p>
                                        <p class="text-primary">{{ $producto->stock_minimo }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado y Fechas -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="bg-light p-3 rounded">
                                <h5 class="border-bottom pb-2">Estado y Fechas</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Estado:</strong></p>
                                        <span class="badge badge-{{ $SystemConstants::getColorEstadoProducto($producto->estado) }}">
                                            {{ $SystemConstants::getEstadoProducto($producto->estado) }}
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Creado:</strong></p>
                                        <p class="text-muted">{{ $producto->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Actualizado:</strong></p>
                                        <p class="text-muted">{{ $producto->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <form action="{{ route('productos.destroy', $producto->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Eliminar Producto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.bg-light {
    background-color: #f8f9fc !important;
}

.text-primary {
    color: #4e73df !important;
}

.badge {
    padding: 8px 12px;
    border-radius: 20px;
}

.rounded {
    border-radius: 10px !important;
}
</style>
@endsection
