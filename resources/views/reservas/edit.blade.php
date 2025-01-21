@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Editar Reserva #{{ $reserva->id }}</h4>
                    <a href="{{ route('reservas.show', $reserva->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('reservas.update', $reserva->id) }}" id="reservaForm">
                        @csrf
                        @method('PUT')

                        <!-- Cliente -->
                        <div class="form-group row">
                            <label for="cliente_id" class="col-md-4 col-form-label text-md-right">Cliente</label>
                            <div class="col-md-6">
                                <select id="cliente_id" class="form-control @error('cliente_id') is-invalid @enderror" name="cliente_id" required>
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $reserva->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="form-group row">
                            <label for="direccion" class="col-md-4 col-form-label text-md-right">Dirección</label>
                            <div class="col-md-6">
                                <input id="direccion"
                                       type="text"
                                       class="form-control @error('direccion') is-invalid @enderror"
                                       name="direccion"
                                       value="{{ old('direccion', $reserva->direccion) }}"
                                       required>
                                @error('direccion')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="form-group row">
                            <label for="fecha_reserva" class="col-md-4 col-form-label text-md-right">Fecha Reserva</label>
                            <div class="col-md-6">
                                <input id="fecha_reserva"
                                       type="date"
                                       class="form-control @error('fecha_reserva') is-invalid @enderror"
                                       name="fecha_reserva"
                                       value="{{ old('fecha_reserva', \Carbon\Carbon::parse($reserva->fecha_reserva)->format('Y-m-d')) }}"
                                       required>
                                @error('fecha_reserva')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fecha_entrega" class="col-md-4 col-form-label text-md-right">Fecha Entrega</label>
                            <div class="col-md-6">
                                <input id="fecha_entrega"
                                       type="date"
                                       class="form-control @error('fecha_entrega') is-invalid @enderror"
                                       name="fecha_entrega"
                                       value="{{ old('fecha_entrega', \Carbon\Carbon::parse($reserva->fecha_entrega)->format('Y-m-d')) }}"
                                       required>
                                @error('fecha_entrega')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="form-group row">
                            <label for="descripcion" class="col-md-4 col-form-label text-md-right">Descripción</label>
                            <div class="col-md-6">
                                <textarea id="descripcion"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          name="descripcion"
                                          rows="3">{{ old('descripcion', $reserva->descripcion) }}</textarea>
                                @error('descripcion')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Productos -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Productos</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-5">
                                        <select id="producto_select" class="form-control select2">
                                            <option value="">Buscar producto...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" id="cantidad_producto" class="form-control" value="1" min="1" placeholder="Cantidad">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" id="precio_producto" class="form-control" readonly placeholder="Precio">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary" id="agregar_producto">
                                            <i class="fas fa-plus"></i> Agregar
                                        </button>
                                    </div>
                                </div>

                                <!-- Tabla de productos existentes -->
                                <div class="table-responsive mt-3">
                                    <table class="table" id="productos_tabla">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Subtotal</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reserva->detalles()->where('estado', 1)->get() as $detalle)
                                                <tr id="detalle-{{ $detalle->id }}">
                                                    <td>{{ $detalle->producto->nombre }}</td>
                                                    <td>{{ $detalle->cantidad }}</td>
                                                    <td>{{ number_format($detalle->precio_unitario, 2) }}</td>
                                                    <td>{{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                                                    <td>
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="eliminarDetalle({{ $reserva->id }}, {{ $detalle->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Total:</th>
                                                <th id="total_reserva">$0.00</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="form-group row">
                            <label for="precio_total" class="col-md-4 col-form-label text-md-right">Precio Total</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="precio_total"
                                           type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="precio_total"
                                           value="{{ number_format($reserva->precio_total, 2) }}"
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0 mt-4">
                            <div class="col-md-8 offset-md-4">
                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary" id="btnSubmit">
                                    <i class="fas fa-save"></i> Actualizar Reserva
                                </button>

                                <!-- Botón Cancelar -->
                                <button type="button" class="btn btn-danger ml-2" id="btnCancelar">
                                    <i class="fas fa-times"></i> Cancelar
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
let precioTotal = {{ $reserva->precio_total }};

function actualizarTotal() {
    let total = 0;
    $('#productos_tabla tbody tr').each(function() {
        const cantidad = $(this).find('td:eq(1) input').val();
        const precioUnitario = $(this).find('td:eq(2)').text().replace('$', '');
        if (cantidad && precioUnitario) {
            total += parseFloat(cantidad) * parseFloat(precioUnitario);
        }
    });
    precioTotal = total;
    $('#precio_total').val(total.toFixed(2));
}

$(document).ready(function() {
    $('#producto_select').select2({
        placeholder: 'Buscar producto...',
        allowClear: true,
        ajax: {
            url: '{{ route("productos.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.nombre,
                            precio: item.precio_alquiler,
                            stock: item.stock
                        };
                    })
                };
            },
            cache: true
        }
    });

    // Actualizar precio cuando se selecciona un producto
    $('#producto_select').on('select2:select', function(e) {
        var data = e.params.data;
        $('#precio_producto').val(data.precio);
    });

    // Agregar producto a la tabla
    $('#agregar_producto').click(function() {
        var producto = $('#producto_select').select2('data')[0];
        if (!producto) {
            alert('Por favor seleccione un producto');
            return;
        }

        var cantidad = parseInt($('#cantidad_producto').val());

        // Validación más estricta del stock
        if (!producto.stock || producto.stock <= 0) {
            alert('Este producto no tiene stock disponible');
            return;
        }

        if (cantidad <= 0) {
            alert('La cantidad debe ser mayor a 0');
            return;
        }

        if (cantidad > producto.stock) {
            alert('Solo hay ' + producto.stock + ' unidades disponibles de este producto');
            return;
        }

        // Verificar si el producto ya existe
        if ($('#detalle-' + producto.id).length > 0) {
            alert('Este producto ya ha sido agregado');
            return;
        }

        var subtotal = cantidad * producto.precio;

        // Agregar fila a la tabla con mensaje de stock
        var newRow = `
            <tr id="detalle-${producto.id}">
                <td>${producto.text}
                    <input type="hidden" name="productos[${producto.id}][id]" value="${producto.id}">
                    <input type="hidden" name="productos[${producto.id}][precio]" value="${producto.precio}">
                    <br><small class="text-muted">Stock disponible: ${producto.stock}</small>
                </td>
                <td>
                    <input type="number"
                           name="productos[${producto.id}][cantidad]"
                           value="${cantidad}"
                           min="1"
                           max="${producto.stock}"
                           class="form-control cantidad-producto"
                           style="width: 100px"
                           onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                </td>
                <td>$${producto.precio}</td>
                <td class="subtotal">$${subtotal.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm eliminar-producto">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#productos_tabla tbody').append(newRow);

        // Limpiar selección
        $('#producto_select').val(null).trigger('change');
        $('#cantidad_producto').val(1);
        $('#precio_producto').val('');

        actualizarTotal();
    });

    // Prevenir doble submit
    $('#reservaForm').submit(function(e) {
        e.preventDefault();
        var submitButton = $(this).find('button[type="submit"]');

        // Validaciones
        if (!$('#cliente_id').val()) {
            mostrarError($('#cliente_id'), 'Debe seleccionar un cliente');
            return false;
        }

        if (!$('#direccion').val().trim()) {
            mostrarError($('#direccion'), 'La dirección es requerida');
            return false;
        }

        if (!$('#fecha_entrega').val()) {
            mostrarError($('#fecha_entrega'), 'La fecha de entrega es requerida');
            return false;
        }

        if (!$('#fecha_reserva').val()) {
            mostrarError($('#fecha_reserva'), 'La fecha del evento es requerida');
            return false;
        }

        // Validar que haya al menos un producto
        if ($('#productos_tabla tbody tr').length === 0) {
            alert('Debe agregar al menos un producto a la reserva');
            return false;
        }

        // Deshabilitar el botón y cambiar el texto
        submitButton.prop('disabled', true)
                   .html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        // Si todo está bien, enviar el formulario
        this.submit();
    });

    // Manejador para el botón Cancelar
    $('#btnCancelar').click(function() {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Se perderán todos los cambios realizados",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, continuar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('reservas.show', $reserva->id) }}";
            }
        });
    });
});

function mostrarError(elemento, mensaje) {
    elemento.addClass('is-invalid');
    elemento.after(`<div class="invalid-feedback">${mensaje}</div>`);
    elemento.focus();
}

function eliminarDetalle(reservaId, detalleId) {
    if (confirm('¿Está seguro que desea eliminar este producto?')) {
        fetch(`/reservas/${reservaId}/detalles/${detalleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar la fila de la tabla
                document.getElementById(`detalle-${detalleId}`).remove();
                // Actualizar el precio total
                document.getElementById('precio-total').textContent =
                    number_format(data.nuevo_precio_total, 2);
                // Mostrar mensaje de éxito
                alert('Producto eliminado correctamente');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el producto');
        });
    }
}

function number_format(number, decimals) {
    return new Intl.NumberFormat('es-PY', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}
</script>
@endpush

@push('styles')
<style>
/* Estilo para el botón volver en el header */
.card-header .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.card-header {
    background-color: #4e73df;
    color: white;
}

.card-header .btn-secondary {
    background-color: transparent;
    border-color: white;
    color: white;
}

.card-header .btn-secondary:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.card {
    border-radius: 15px;
}

.form-control {
    border-radius: 5px;
}

.input-group-text {
    border-radius: 5px 0 0 5px;
}

.producto-item {
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.producto-item:hover {
    background-color: #e9ecef;
}

.select2-container {
    width: 100% !important;
}
.select2-container .select2-selection--single {
    height: 38px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@endsection
