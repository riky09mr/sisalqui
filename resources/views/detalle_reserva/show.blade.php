@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Detalle de Reserva #{{ $detalleReserva->id }}</h3>
                        <div>
                            <a href="{{ route('detalle-reserva.edit', $detalleReserva->id) }}" class="btn btn-light mr-2">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('reservas.show', $detalleReserva->reserva_id) }}" class="btn btn-light">
                                <i class="fas fa-arrow-left"></i> Volver a la Reserva
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="bg-light p-3 rounded">
                                <h5 class="border-bottom pb-2">Información de la Reserva</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Reserva #:</strong></p>
                                        <p class="text-primary">
                                            <a href="{{ route('reservas.show', $detalleReserva->reserva_id) }}">
                                                {{ $detalleReserva->reserva_id }}
                                            </a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Cliente:</strong></p>
                                        <p class="text-primary">{{ $detalleReserva->reserva->cliente->nombre }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="bg-light p-3 rounded">
                                <h5 class="border-bottom pb-2">Información del Producto</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Producto:</strong></p>
                                        <p class="text-primary">{{ $detalleReserva->producto->nombre }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Precio Unitario:</strong></p>
                                        <p class="text-primary">${{ number_format($detalleReserva->precio_unitario, 2) }}</p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Cantidad:</strong></p>
                                        <p class="text-primary">{{ $detalleReserva->cantidad }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Subtotal:</strong></p>
                                        <p class="text-primary">${{ number_format($detalleReserva->subtotal, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="bg-light p-3 rounded">
                                <h5 class="border-bottom pb-2">Información Adicional</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Creado el:</strong></p>
                                        <p class="text-muted">{{ $detalleReserva->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Última actualización:</strong></p>
                                        <p class="text-muted">{{ $detalleReserva->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <form action="{{ route('detalle-reserva.destroy', $detalleReserva->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar este detalle?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Eliminar Detalle
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

.rounded {
    border-radius: 10px !important;
}
</style>
@endsection
