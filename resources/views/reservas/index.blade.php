@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Gestión de Reservas</h3>
                        <div>
                            <a href="{{ route('reservas.create') }}" class="btn btn-light btn-sm mr-2">
                                <i class="fas fa-plus"></i> Nueva Reserva
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <form action="{{ route('reservas.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="Buscar por nombre o cédula..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="estado" class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="2" {{ request('estado') == '2' ? 'selected' : '' }}>Confirmado</option>
                                    <option value="3" {{ request('estado') == '3' ? 'selected' : '' }}>Cancelado</option>
                                    <option value="4" {{ request('estado') == '4' ? 'selected' : '' }}>Entregado</option>
                                    <option value="5" {{ request('estado') == '5' ? 'selected' : '' }}>Retirado</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                            <div class="col-md-3 text-right">
                                @if(request()->hasAny(['search', 'estado']))
                                    <a href="{{ route('reservas.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Limpiar filtros
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Fecha Reserva</th>
                                    <th>Fecha Entrega</th>
                                    <th>Precio Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reservas as $reserva)
                                    <tr>
                                        <td>{{ $reserva->id }}</td>
                                        <td>{{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}</td>
                                        <td>{{ $reserva->fecha_reserva_formateada }}</td>
                                        <td>{{ $reserva->fecha_entrega_formateada }}</td>
                                        <td>${{ number_format($reserva->precio_total, 2) }}</td>
                                        <td>
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
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Entregada
                                                        <br>
                                                        <small>{{ $reserva->updated_at->format('d/m/Y H:i') }}</small>
                                                    </span>
                                                    @break
                                                @case(5)
                                                    <span class="badge badge-secondary">Retirada</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">Desconocido</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('reservas.show', $reserva) }}" class="btn btn-primary btn-sm" title="Ver Detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('reservas.ver-pdf', $reserva) }}" class="btn btn-danger btn-sm" target="_blank" title="Ver PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>

                                                @if(!in_array($reserva->estado, [3, 4, 5])) {{-- No mostrar editar ni acciones si está cancelada, entregada o retirada --}}
                                                    <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-warning btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Acciones
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @if($reserva->estado == 1)
                                                                <button type="button" class="dropdown-item text-success"
                                                                        onclick="confirmarAccion('{{ $reserva->id }}', 'confirmar')">
                                                                    <i class="fas fa-check"></i> Confirmar
                                                                </button>
                                                            @endif

                                                            <button type="button" class="dropdown-item text-danger"
                                                                    onclick="cancelarAccion('{{ $reserva->id }}', 'cancelar')">
                                                                <i class="fas fa-times"></i> Cancelar
                                                            </button>

                                                            @if($reserva->estado == 2)
                                                                <a href="{{ route('entregas.index') }}" class="dropdown-item text-primary">
                                                                    <i class="fas fa-truck"></i> Ir a Entregas
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay reservas registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $reservas->appends(request()->query())->links() }}
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

.btn-group .btn {
    margin: 0 2px;
}

.badge {
    padding: 8px 12px;
    border-radius: 20px;
}

/* Estilo para los botones en el header */
.card-header .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.card-header .btn-secondary {
    background-color: transparent;
    border-color: white;
    color: white;
}

.card-header .btn-secondary:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.mr-2 {
    margin-right: 0.5rem;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarAccion(reservaId, accion) {
    Swal.fire({
        title: 'Confirmar Reserva',
        text: '¿Está seguro que desea confirmar esta reserva?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'No, volver'
    }).then((result) => {
        if (result.isConfirmed) {
            enviarFormulario(reservaId, 'confirmar');
        }
    });
}

function cancelarAccion(reservaId, accion) {
    Swal.fire({
        title: 'Cancelar Reserva',
        text: '¿Está seguro que desea cancelar esta reserva?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'No, volver'
    }).then((result) => {
        if (result.isConfirmed) {
            enviarFormulario(reservaId, 'cancelar');
        }
    });
}

function enviarFormulario(reservaId, accion) {
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('reservas') }}/${reservaId}/${accion}`;

    // Token CSRF
    let csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    // Método PUT
    let methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';
    form.appendChild(methodField);

    document.body.appendChild(form);
    form.submit();
}

// Actualizar automáticamente al cambiar el estado
document.querySelector('select[name="estado"]').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush
@endsection
