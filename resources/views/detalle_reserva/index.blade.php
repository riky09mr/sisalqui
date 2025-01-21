@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Detalles de Reservas</h3>
                        <div>
                            <a href="{{ route('reservas.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left"></i> Volver a Reservas
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
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
                                    <th>Reserva #</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->id }}</td>
                                        <td>
                                            <a href="{{ route('reservas.show', $detalle->reserva_id) }}">
                                                {{ $detalle->reserva_id }}
                                            </a>
                                        </td>
                                        <td>{{ $detalle->producto->nombre }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('detalle-reserva.show', $detalle->id) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('detalle-reserva.edit', $detalle->id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('detalle-reserva.destroy', $detalle->id) }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('¿Está seguro de eliminar este detalle?')"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay detalles de reservas registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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

.table td {
    vertical-align: middle;
}
</style>
@endsection
