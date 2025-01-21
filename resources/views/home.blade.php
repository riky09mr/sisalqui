@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Reservas por Entregar -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar"></i> Reservas por Entregar</h5>
                </div>
                <div class="card-body">
                    <!-- Entregas para Hoy -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <i class="fas fa-clock"></i> Entregas para Hoy ({{ now()->format('d/m/Y') }})
                        </h6>
                        @if($reservasHoy->count() > 0)
                            @foreach($reservasHoy as $reserva)
                                <div class="alert alert-info mb-2">
                                    <strong>{{ $reserva->cliente->nombre }}</strong>
                                    <br>
                                    <small>Dirección: {{ $reserva->direccion }}</small>
                                    <a href="{{ route('reservas.show', $reserva) }}" class="float-right">
                                        <i class="fas fa-eye"></i> Ver detalles
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No hay entregas programadas para hoy</p>
                        @endif
                    </div>

                    <!-- Entregas para Mañana -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">
                            <i class="fas fa-calendar-day"></i> Entregas para Mañana ({{ now()->addDay()->format('d/m/Y') }})
                        </h6>
                        @if($reservasManana->count() > 0)
                            @foreach($reservasManana as $reserva)
                                <div class="alert alert-success mb-2">
                                    <strong>{{ $reserva->cliente->nombre }}</strong>
                                    <br>
                                    <small>Dirección: {{ $reserva->direccion }}</small>
                                    <a href="{{ route('reservas.show', $reserva) }}" class="float-right">
                                        <i class="fas fa-eye"></i> Ver detalles
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No hay entregas programadas para mañana</p>
                        @endif
                    </div>

                    <!-- Entregas para Pasado Mañana -->
                    <div>
                        <h6 class="border-bottom pb-2">
                            <i class="fas fa-calendar-week"></i> Entregas para {{ now()->addDays(2)->format('d/m/Y') }}
                        </h6>
                        @if($reservasPasadoManana->count() > 0)
                            @foreach($reservasPasadoManana as $reserva)
                                <div class="alert alert-warning mb-2">
                                    <strong>{{ $reserva->cliente->nombre }}</strong>
                                    <br>
                                    <small>Dirección: {{ $reserva->direccion }}</small>
                                    <a href="{{ route('reservas.show', $reserva) }}" class="float-right">
                                        <i class="fas fa-eye"></i> Ver detalles
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No hay entregas programadas para pasado mañana</p>
                        @endif
                    </div>

                    <!-- Entregas Futuras -->
                    <div>
                        <h6 class="border-bottom pb-2">
                            <i class="fas fa-calendar-alt"></i> Próximas Entregas
                        </h6>
                        @if($reservasFuturas->count() > 0)
                            @foreach($reservasFuturas as $reserva)
                                <div class="alert alert-secondary mb-2">
                                    <strong>{{ $reserva->cliente->nombre }}</strong>
                                    <br>
                                    <small>Fecha de Entrega: {{ Carbon\Carbon::parse($reserva->fecha_entrega)->format('d/m/Y') }}</small>
                                    <br>
                                    <small>Dirección: {{ $reserva->direccion }}</small>
                                    <a href="{{ route('reservas.show', $reserva) }}" class="float-right">
                                        <i class="fas fa-eye"></i> Ver detalles
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No hay entregas programadas para fechas posteriores</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado de Pedidos -->
        <div class="col-md-4">
            <!-- Pedidos por Retirar -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Pedidos por Retirar</h5>
                </div>
                <div class="card-body">
                    @if($pedidosPorRetirar->count() > 0)
                        @foreach($pedidosPorRetirar as $pedido)
                            <div class="alert alert-warning mb-2">
                                <strong>{{ $pedido->cliente->nombre }}</strong>
                                <br>
                                <small>Dirección: {{ $pedido->direccion }}</small>
                                <a href="{{ route('reservas.show', $pedido) }}" class="float-right">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay pedidos pendientes por retirar</p>
                    @endif
                </div>
            </div>

            <!-- Pedidos Entregados Hoy -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check"></i> Pedidos Entregados Hoy</h5>
                </div>
                <div class="card-body">
                    @if($pedidosEntregadosHoy->count() > 0)
                        @foreach($pedidosEntregadosHoy as $pedido)
                            <div class="alert alert-success mb-2">
                                <strong>{{ $pedido->cliente->nombre }}</strong>
                                <br>
                                <small>Dirección: {{ $pedido->direccion }}</small>
                                <a href="{{ route('reservas.show', $pedido) }}" class="float-right">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay pedidos entregados hoy</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    border: none;
}

.border-left-primary {
    border-left: .25rem solid #4e73df !important;
}

.border-left-success {
    border-left: .25rem solid #1cc88a !important;
}

.list-group-item-action:hover {
    background-color: #f8f9fc;
    color: #4e73df;
}

.bg-success {
    background-color: #1cc88a !important;
}
</style>
@endsection
