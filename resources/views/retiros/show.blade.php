@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Procesar Retiro</h4>
                        <a href="{{ route('retiros.index') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Información del cliente -->
                    <div class="alert alert-info">
                        <h5>Cliente: {{ $reserva->cliente->nombre }} {{ $reserva->cliente->apellido }}</h5>
                        <p class="mb-1"><strong>Teléfono:</strong> {{ $reserva->cliente->telefono }}</p>
                        <p class="mb-1"><strong>Dirección:</strong> {{ $reserva->direccion }}</p>
                        <p class="mb-0"><strong>Fecha de entrega:</strong>
                            {{ \Carbon\Carbon::parse($reserva->updated_at)->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <form action="{{ route('retiros.procesar', $reserva) }}" method="POST" id="retiroForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad Entregada</th>
                                        <th>Retiro Completo</th>
                                        <th>Cantidad Retirada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reserva->detalles->where('estado', 1) as $detalle)
                                        <tr>
                                            <td>{{ $detalle->producto->nombre }}</td>
                                            <td>{{ $detalle->cantidad }}</td>
                                            <td class="text-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox"
                                                           class="custom-control-input retiro-completo"
                                                           id="retiro_completo_{{ $detalle->id }}"
                                                           name="detalles[{{ $detalle->id }}][retiro_completo]"
                                                           data-detalle-id="{{ $detalle->id }}">
                                                    <label class="custom-control-label"
                                                           for="retiro_completo_{{ $detalle->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control cantidad-retirada"
                                                       name="detalles[{{ $detalle->id }}][cantidad_retirada]"
                                                       min="0"
                                                       max="{{ $detalle->cantidad }}"
                                                       data-detalle-id="{{ $detalle->id }}"
                                                       placeholder="Cantidad retirada">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Procesar Retiro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('retiroForm');

    // Manejar la interacción entre checkbox y campo de cantidad
    document.querySelectorAll('.retiro-completo').forEach(checkbox => {
        const detalleId = checkbox.dataset.detalleId;
        const cantidadInput = document.querySelector(`.cantidad-retirada[data-detalle-id="${detalleId}"]`);

        checkbox.addEventListener('change', function() {
            cantidadInput.disabled = this.checked;
            if (this.checked) {
                cantidadInput.value = '';
            }
        });

        cantidadInput.addEventListener('input', function() {
            checkbox.checked = false;
            checkbox.disabled = this.value.length > 0;
        });
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        let isValid = true;
        const detalles = document.querySelectorAll('tbody tr');

        detalles.forEach(tr => {
            const detalleId = tr.querySelector('.retiro-completo').dataset.detalleId;
            const checkbox = tr.querySelector('.retiro-completo');
            const cantidadInput = tr.querySelector('.cantidad-retirada');

            if (!checkbox.checked && !cantidadInput.value) {
                isValid = false;
            }
        });

        if (!isValid) {
            alert('Por favor, indique la cantidad retirada o marque como retiro completo para todos los productos.');
            return;
        }

        this.submit();
    });
});
</script>
@endpush
@endsection
