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
                        <h3 class="mb-0">Editar Producto #{{ $producto->id }}</h3>
                        <a href="{{ route('productos.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('productos.update', $producto->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-md-right">Nombre</label>
                            <div class="col-md-6">
                                <input id="nombre"
                                       type="text"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       name="nombre"
                                       value="{{ old('nombre', $producto->nombre) }}"
                                       required>
                                @error('nombre')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="descripcion" class="col-md-4 col-form-label text-md-right">Descripción</label>
                            <div class="col-md-6">
                                <textarea id="descripcion"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          name="descripcion"
                                          rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                                @error('descripcion')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costo_compra" class="col-md-4 col-form-label text-md-right">Costo de Compra</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="costo_compra"
                                           type="number"
                                           step="0.01"
                                           class="form-control @error('costo_compra') is-invalid @enderror"
                                           name="costo_compra"
                                           value="{{ old('costo_compra', $producto->costo_compra) }}"
                                           required>
                                </div>
                                @error('costo_compra')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="precio_alquiler" class="col-md-4 col-form-label text-md-right">Precio de Alquiler</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="precio_alquiler"
                                           type="number"
                                           step="0.01"
                                           class="form-control @error('precio_alquiler') is-invalid @enderror"
                                           name="precio_alquiler"
                                           value="{{ old('precio_alquiler', $producto->precio_alquiler) }}"
                                           required>
                                </div>
                                @error('precio_alquiler')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stock" class="col-md-4 col-form-label text-md-right">Stock</label>
                            <div class="col-md-6">
                                <input id="stock"
                                       type="number"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       name="stock"
                                       value="{{ old('stock', $producto->stock) }}"
                                       required>
                                @error('stock')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="categoria" class="col-md-4 col-form-label text-md-right">Categoría</label>
                            <div class="col-md-6">
                                <select id="categoria"
                                        class="form-control @error('categoria') is-invalid @enderror"
                                        name="categoria"
                                        required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($SystemConstants::CATEGORIAS_PRODUCTO as $key => $categoria)
                                        <option value="{{ $key }}"
                                                {{ old('categoria', $producto->categoria) == $key ? 'selected' : '' }}>
                                            {{ $categoria }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stock_minimo" class="col-md-4 col-form-label text-md-right">Stock Mínimo</label>
                            <div class="col-md-6">
                                <input id="stock_minimo"
                                       type="number"
                                       class="form-control @error('stock_minimo') is-invalid @enderror"
                                       name="stock_minimo"
                                       value="{{ old('stock_minimo', $producto->stock_minimo) }}"
                                       required>
                                @error('stock_minimo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="estado" class="col-md-4 col-form-label text-md-right">Estado</label>
                            <div class="col-md-6">
                                <select id="estado"
                                        class="form-control @error('estado') is-invalid @enderror"
                                        name="estado"
                                        required>
                                    @foreach($SystemConstants::ESTADOS_PRODUCTO as $key => $estado)
                                        <option value="{{ $key }}"
                                                {{ old('estado', $producto->estado) == $key ? 'selected' : '' }}>
                                            {{ $estado }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Producto
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.form-control {
    border-radius: 5px;
}

.input-group-text {
    border-radius: 5px 0 0 5px;
}
</style>
@endsection
