@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Pedidos por Retirar -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Pedidos por Retirar</h4>
                </div>
                <div class="card-body">
                    @if($reservasPorRetirar->count() > 0)
                        @foreach($reservasPorRetirar as $reserva)
                            <div class="alert alert-warning mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-1">
                                            Cliente: {{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}
                                        </h5>
                                        <p class="mb-1">
                                            <strong>Entregado el:</strong>
                                            {{ \Carbon\Carbon::parse($reserva->updated_at)->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Dirección:</strong> {{ $reserva->direccion }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Total:</strong> ${{ number_format($reserva->precio_total, 2) }}
                                        </p>
                                        <div class="mt-2">
                                            <strong>Productos:</strong>
                                            <ul class="list-unstyled ml-3">
                                                @foreach($reserva->detalles->where('estado', 1) as $detalle)
                                                    <li>
                                                        {{ $detalle->producto->nombre }}
                                                        ({{ $detalle->cantidad }} unidades)
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a href="{{ route('retiros.show', ['reserva' => $reserva->id]) }}"
                                           class="btn btn-warning mb-2 w-100">
                                            <i class="fas fa-clipboard-check"></i> Procesar Retiro
                                        </a>
                                        <a href="{{ route('reservas.show', $reserva) }}"
                                           class="btn btn-primary w-100">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay pedidos pendientes de retiro</p>
                    @endif
                </div>
            </div>

            <!-- Retiros del Día -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">Retiros Realizados Hoy</h4>
                </div>
                <div class="card-body">
                    @if($reservasRetiradas->count() > 0)
                        @foreach($reservasRetiradas as $reserva)
                            <div class="alert alert-secondary mb-3">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5 class="mb-1">
                                            Cliente: {{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}
                                        </h5>
                                        <p class="mb-1">
                                            <strong>Retirado:</strong>
                                            {{ $reserva->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Total:</strong> ${{ number_format($reserva->precio_total, 2) }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Estado:</strong>
                                            @if($reserva->retiro_completo)
                                                <span class="badge badge-success">Retiro Completo</span>
                                            @else
                                                <span class="badge badge-warning">Retiro Parcial</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <a href="{{ route('reservas.show', $reserva) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay retiros realizados hoy</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
