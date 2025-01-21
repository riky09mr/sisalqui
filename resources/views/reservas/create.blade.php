@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Nueva Reserva</h4>
                    <a href="{{ route('reservas.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('reservas.store') }}" id="reservaForm">
                        @csrf

                        <!-- Selección de Cliente -->
                        <div class="form-group row">
                            <label for="cliente_id" class="col-md-4 col-form-label text-md-right">Cliente</label>
                            <div class="col-md-6">
                                <select name="cliente_id" id="cliente_id" class="form-control select2-clientes @error('cliente_id') is-invalid @enderror" required>
                                    <option value="">Buscar cliente por nombre o cédula...</option>
                                </select>
                                @error('cliente_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Dirección de Entrega -->
                        <div class="form-group row">
                            <label for="direccion" class="col-md-4 col-form-label text-md-right">Dirección</label>
                            <div class="col-md-6">
                                <textarea name="direccion"
                                          id="direccion"
                                          class="form-control @error('direccion') is-invalid @enderror"
                                          required>{{ old('direccion') }}</textarea>
                                @error('direccion')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="form-group row">
                            <label for="fecha_entrega" class="col-md-4 col-form-label text-md-right">Fecha de Entrega</label>
                            <div class="col-md-6">
                                <input type="date"
                                       id="fecha_entrega"
                                       name="fecha_entrega"
                                       class="form-control @error('fecha_entrega') is-invalid @enderror"
                                       value="{{ old('fecha_entrega') }}"
                                       required>
                                @error('fecha_entrega')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fecha_reserva" class="col-md-4 col-form-label text-md-right">Fecha del Evento</label>
                            <div class="col-md-6">
                                <input type="date"
                                       id="fecha_reserva"
                                       name="fecha_reserva"
                                       class="form-control @error('fecha_reserva') is-invalid @enderror"
                                       value="{{ old('fecha_reserva') }}"
                                       required>
                                @error('fecha_reserva')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Agregar campo descripcion -->
                        <div class="form-group row">
                            <label for="descripcion" class="col-md-4 col-form-label text-md-right">Descripción</label>
                            <div class="col-md-6">
                                <textarea name="descripcion"
                                          id="descripcion"
                                          class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Sección de Productos -->
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

                        <div class="form-group row mb-0 mt-4">
                            <div class="col-md-8 offset-md-4">
                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary" id="btnSubmit">
                                    <i class="fas fa-save"></i> Crear Reserva
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
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Estilos generales del formulario */
.card {
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background-color: #4e73df;
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 1rem 1.5rem;
}

.card-header h4 {
    margin-bottom: 0;
    font-weight: 600;
}

.card-body {
    padding: 2rem;
}

/* Estilos para los inputs y selects */
.form-control {
    border-radius: 5px;
    border: 1px solid #e3e6f0;
    padding: 0.75rem;
    font-size: 0.9rem;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Estilos específicos para Select2 */
.select2-container .select2-selection--single {
    height: 45px;
    border: 1px solid #e3e6f0;
    border-radius: 5px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 45px;
    padding-left: 15px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 43px;
}

/* Estilos para la sección de productos */
.table {
    margin-bottom: 0;
}

.table thead th {
    border-top: none;
    border-bottom: 2px solid #e3e6f0;
    font-weight: 600;
    color: #4e73df;
}

.table td {
    vertical-align: middle;
}

/* Estilos para botones */
.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2e59d9;
}

.btn-danger {
    background-color: #e74a3b;
    border-color: #e74a3b;
}

.btn-danger:hover {
    background-color: #be2617;
    border-color: #be2617;
}

/* Estilos para mensajes de validación */
.invalid-feedback {
    display: block;
    margin-top: 0.25rem;
    font-size: 80%;
    color: #e74a3b;
}

.is-invalid {
    border-color: #e74a3b !important;
}

/* Estilos para la sección de productos */
#productos_tabla {
    border-radius: 5px;
    overflow: hidden;
}

.cantidad-producto {
    width: 100px !important;
    text-align: center;
}

/* Estilos para el total */
#total_reserva {
    font-size: 1.2rem;
    font-weight: 600;
    color: #4e73df;
}

/* Estilos para las etiquetas */
.col-form-label {
    font-weight: 500;
    color: #5a5c69;
}

/* Estilos para el card de productos */
.card .card-header h5 {
    color: white;
    font-weight: 600;
    margin-bottom: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .col-form-label {
        text-align: left !important;
        margin-bottom: 0.5rem;
    }
}

/* Animaciones */
.btn {
    transition: all 0.2s ease-in-out;
}

.form-control {
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

/* Estilos para el botón de eliminar */
.eliminar-producto {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* Estilos para pequeños textos informativos */
.text-muted {
    font-size: 0.875rem;
}

/* Mejoras en la tabla de productos */
.table-responsive {
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.table thead th {
    background-color: #f8f9fc;
    padding: 1rem;
}

.table tbody td {
    padding: 1rem;
}

/* Estilo para el input readonly */
input[readonly] {
    background-color: #f8f9fc;
    cursor: not-allowed;
}

/* Estilo para Select2 con error */
.select2-container .select2-selection.is-invalid {
    border-color: #e74a3b;
}

/* Estilo para el botón deshabilitado */
button:disabled {
    cursor: not-allowed;
    opacity: 0.65;
}

/* Estilos para los botones */
.btn {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: all 0.15s ease-in-out;
}

.btn i {
    margin-right: 0.5rem;
}

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.ml-2 {
    margin-left: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .form-group.row .col-md-8 {
        text-align: center;
    }

    .btn {
        margin: 0.5rem;
    }
}

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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-clientes').select2({
        placeholder: 'Buscar cliente por nombre o cédula...',
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
            url: '{{ route("clientes.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                console.log('Datos recibidos:', data); // Debug
                return {
                    results: data.map(function(cliente) {
                        return {
                            id: cliente.id,
                            text: cliente.nombre + ' ' + cliente.apellido + ' - Cédula: ' + cliente.cedula
                        };
                    })
                };
            },
            cache: true
        }
    });

    // Inicializar Select2 para productos con búsqueda AJAX
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
                    fecha: $('#fecha_reserva').val()
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.text,
                            precio: item.precio_alquiler,
                            stock: item.stock_disponible
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

    // Actualizar productos disponibles cuando cambia la fecha
    $('#fecha_reserva').on('change', function() {
        // Limpiar selección de productos
        $('#producto_select').val(null).trigger('change');
        $('#productos_tabla tbody').empty();
        actualizarTotal();
    });

    // Prevenir doble submit
    $('#reservaForm').submit(function(e) {
        e.preventDefault();

        // Obtener el botón de submit
        var submitButton = $(this).find('button[type="submit"]');

        // Validar cliente
        if (!$('#cliente_id').val()) {
            mostrarError($('#cliente_id'), 'Debe seleccionar un cliente');
            return false;
        }

        // Validar dirección
        if (!$('#direccion').val().trim()) {
            mostrarError($('#direccion'), 'La dirección es requerida');
            return false;
        }

        // Validar fechas
        if (!$('#fecha_entrega').val()) {
            mostrarError($('#fecha_entrega'), 'La fecha de entrega es requerida');
            return false;
        }

        if (!$('#fecha_reserva').val()) {
            mostrarError($('#fecha_reserva'), 'La fecha del evento es requerida');
            return false;
        }

        // Validar productos
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

    // Limpiar errores al cambiar valor
    $('#cliente_id').on('change', function() {
        limpiarError($(this));
    });

    $('#direccion').on('input', function() {
        limpiarError($(this));
    });

    $('#fecha_entrega, #fecha_reserva').on('change', function() {
        limpiarError($(this));
    });

    // Función para mostrar mensaje de error (actualizada)
    function mostrarError(input, mensaje) {
        // Remover mensaje anterior si existe
        input.closest('.form-group').find('.invalid-feedback').remove();

        // Agregar clase de error al input
        input.addClass('is-invalid');

        // Para Select2, agregar clase al contenedor
        if (input.hasClass('select2')) {
            input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
        }

        // Agregar nuevo mensaje
        $(`<div class="invalid-feedback">${mensaje}</div>`)
            .insertAfter(input.hasClass('select2') ? input.next('.select2-container') : input);
    }

    // Función para limpiar error (actualizada)
    function limpiarError(input) {
        input.removeClass('is-invalid');
        if (input.hasClass('select2')) {
            input.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
        input.closest('.form-group').find('.invalid-feedback').remove();
    }

    // Actualizar subtotal cuando cambia la cantidad
    $(document).on('change', '.cantidad-producto', function() {
        var row = $(this).closest('tr');
        var cantidad = parseInt($(this).val());
        var maxStock = parseInt($(this).attr('max'));

        // Validar que la cantidad no exceda el stock
        if (cantidad > maxStock) {
            alert('La cantidad no puede exceder el stock disponible (' + maxStock + ' unidades)');
            $(this).val(maxStock);
            cantidad = maxStock;
        }

        if (cantidad < 1) {
            alert('La cantidad debe ser al menos 1');
            $(this).val(1);
            cantidad = 1;
        }

        var precio = parseFloat(row.find('input[name$="[precio]"]').val());
        var subtotal = cantidad * precio;
        row.find('.subtotal').text('$' + subtotal.toFixed(2));
        actualizarTotal();
    });

    // Agregar producto a la tabla (modificado con validación adicional)
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
        if ($('#producto_row_' + producto.id).length > 0) {
            alert('Este producto ya ha sido agregado');
            return;
        }

        var subtotal = cantidad * producto.precio;

        // Agregar fila a la tabla con mensaje de stock
        var newRow = `
            <tr id="producto_row_${producto.id}">
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

    // Eliminar producto
    $(document).on('click', '.eliminar-producto', function() {
        $(this).closest('tr').remove();
        actualizarTotal();
    });

    // Función para actualizar el total
    function actualizarTotal() {
        var total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).text().replace('$', ''));
        });
        $('#total_reserva').text('$' + total.toFixed(2));
    }

    // Manejador para el botón Cancelar
    $('#btnCancelar').click(function() {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Se perderán todos los datos ingresados",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, continuar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('reservas.index') }}";
            }
        });
    });
});
</script>
@endpush
