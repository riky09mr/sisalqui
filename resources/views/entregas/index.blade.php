@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Filtros y Búsqueda -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('entregas.index') }}" method="GET" class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label for="search">Buscar por Cliente:</label>
                            <input type="text" name="search" id="search" class="form-control"
                                   value="{{ request('search') }}"
                                   placeholder="Nombre o apellido del cliente">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha">Fecha de Entrega:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control"
                                   value="{{ request('fecha', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('entregas.ver-pdf') }}?fecha={{ request('fecha', date('Y-m-d')) }}"
                               class="btn btn-danger w-100" target="_blank">
                                <i class="fas fa-file-pdf"></i> Ver PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reservas por Entregar -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Reservas por Entregar</h4>
                </div>
                <div class="card-body">
                    @if($reservasConfirmadas->count() > 0)
                        @foreach($reservasConfirmadas as $reserva)
                            <div class="alert alert-info mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-1">
                                            Cliente: {{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}
                                        </h5>
                                        <p class="mb-1">
                                            <strong>Fecha de Entrega:</strong>
                                            {{ \Carbon\Carbon::parse($reserva->fecha_entrega)->format('d/m/Y') }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Dirección:</strong> {{ $reserva->direccion }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Total:</strong> ${{ number_format($reserva->precio_total, 2) }}
                                        </p>
                                        <!-- Productos -->
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
                                        <button class="btn btn-success mb-2 w-100"
                                                onclick="entregarReserva({{ $reserva->id }})">
                                            <i class="fas fa-check"></i> Marcar como Entregado
                                        </button>
                                        <a href="{{ route('reservas.show', $reserva) }}"
                                           class="btn btn-primary w-100">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay reservas pendientes de entrega</p>
                    @endif
                </div>
            </div>

            <!-- Entregas del Día -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Entregas Realizadas Hoy</h4>
                </div>
                <div class="card-body">
                    @if($reservasEntregadas->count() > 0)
                        @foreach($reservasEntregadas as $reserva)
                            <div class="alert alert-success mb-3">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5 class="mb-1">
                                            Cliente: {{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}
                                        </h5>
                                        <p class="mb-1">
                                            <strong>Entregado:</strong>
                                            {{ $reserva->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Total:</strong> ${{ number_format($reserva->precio_total, 2) }}
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
                        <p class="text-muted">No hay entregas realizadas hoy</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function entregarReserva(reservaId) {
    if (!confirm('¿Está seguro de marcar esta reserva como entregada?')) {
        return;
    }

    fetch(`/entregas/${reservaId}/entregar`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Reserva entregada exitosamente');
            location.reload();
        } else {
            alert(data.message || 'Error al entregar la reserva');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
}
</script>
@endpush
@endsection
