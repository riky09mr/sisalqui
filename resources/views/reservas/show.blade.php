@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Detalles de la Reserva</h4>
                </div>

                <div class="card-body">
                    <!-- Información del cliente -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Información del Cliente</h5>
                            <p><strong>Nombre:</strong> {{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}</p>
                            <p><strong>Teléfono:</strong> {{ $reserva->cliente->telefono }}</p>
                            <p><strong>Email:</strong> {{ $reserva->cliente->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Información de la Reserva</h5>
                            <p><strong>Fecha de Reserva:</strong> {{ $reserva->created_at->format('d/m/Y') }}</p>
                            <p><strong>Fecha de Entrega:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_entrega)->format('d/m/Y') }}</p>
                            <p><strong>Estado:</strong>
                                @switch($reserva->estado)
                                    @case(1)
                                        <span class="badge badge-warning">Pendiente</span>
                                        @break
                                    @case(2)
                                        <span class="badge badge-info">Confirmada</span>
                                        @break
                                    @case(3)
                                        <span class="badge badge-danger">Cancelada</span>
                                        @break
                                    @case(4)
                                        <span class="badge badge-success">Entregada</span>
                                        @break
                                    @case(5)
                                        <span class="badge badge-secondary">Retirada</span>
                                        @break
                                @endswitch
                            </p>
                            <p><strong>Dirección de Entrega:</strong> {{ $reserva->direccion }}</p>
                        </div>
                    </div>

                    <!-- Detalles de productos -->
                    <h5>Productos Reservados</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reserva->detalles->where('estado', 1) as $detalle)
                                    <tr>
                                        <td>{{ $detalle->producto->nombre }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                        <td>${{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($reserva->precio_total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="btn-group">
                        <a href="{{ URL::previous() }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="{{ route('reservas.ver-pdf', $reserva) }}"
                           class="btn btn-danger"
                           target="_blank">
                            <i class="fas fa-file-pdf"></i> Ver PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
