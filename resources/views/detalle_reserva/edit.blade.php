@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Editar Detalle de Reserva #{{ $detalleReserva->id }}</h3>
                        <a href="{{ route('reservas.show', $detalleReserva->reserva_id) }}" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Volver a la Reserva
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('detalle-reserva.update', $detalleReserva->id) }}" id="detalleForm">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Reserva</label>
                            <div class="col-md-6">
                                <input type="text"
                                       class="form-control"
                                       value="Reserva #{{ $detalleReserva->reserva_id }} - {{ $detalleReserva->reserva->cliente->nombre }}"
                                       readonly>
                                <input type="hidden" name="reserva_id" value="{{ $detalleReserva->reserva_id }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="producto_id" class="col-md-4 col-form-label text-md-right">Producto</label>
                            <div class="col-md-6">
                                <select id="producto_id"
                                        class="form-control @error('producto_id') is-invalid @enderror"
                                        name="producto_id"
                                        required
                                        onchange="actualizarPrecio()">
                                    <option value="">Seleccione un producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                                data-precio="{{ $producto->precio }}"
                                                {{ old('producto_id', $detalleReserva->producto_id) == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->nombre }} - ${{ number_format($producto->precio, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('producto_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidad" class="col-md-4 col-form-label text-md-right">Cantidad</label>
                            <div class="col-md-6">
                                <input id="cantidad"
                                       type="number"
                                       class="form-control @error('cantidad') is-invalid @enderror"
                                       name="cantidad"
                                       value="{{ old('cantidad', $detalleReserva->cantidad) }}"
                                       required
                                       min="1"
                                       onchange="calcularSubtotal()">
                                @error('cantidad')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="precio_unitario" class="col-md-4 col-form-label text-md-right">Precio Unitario</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="precio_unitario"
                                           type="number"
                                           step="0.01"
                                           class="form-control @error('precio_unitario') is-invalid @enderror"
                                           name="precio_unitario"
                                           value="{{ old('precio_unitario', $detalleReserva->precio_unitario) }}"
                                           required
                                           readonly>
                                </div>
                                @error('precio_unitario')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="subtotal" class="col-md-4 col-form-label text-md-right">Subtotal</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="subtotal"
                                           type="number"
                                           step="0.01"
                                           class="form-control"
                                           value="{{ $detalleReserva->subtotal }}"
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Detalle
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function actualizarPrecio() {
    const select = document.getElementById('producto_id');
    const precioInput = document.getElementById('precio_unitario');
    const option = select.options[select.selectedIndex];

    if (option.value) {
        precioInput.value = option.dataset.precio;
        calcularSubtotal();
    }
}

function calcularSubtotal() {
    const cantidad = document.getElementById('cantidad').value;
    const precio = document.getElementById('precio_unitario').value;
    const subtotalInput = document.getElementById('subtotal');

    if (cantidad && precio) {
        subtotalInput.value = (cantidad * precio).toFixed(2);
    }
}

// Calcular subtotal inicial
document.addEventListener('DOMContentLoaded', function() {
    calcularSubtotal();
});
</script>
@endpush

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
