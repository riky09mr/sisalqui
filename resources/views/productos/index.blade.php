@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Productos</h3>
                        <a href="{{ route('productos.create') }}" class="btn btn-light">
                            <i class="fas fa-plus"></i> Nuevo Producto
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros de búsqueda -->
                    <form action="{{ route('productos.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text"
                                           class="form-control"
                                           id="nombre"
                                           name="nombre"
                                           value="{{ request('nombre') }}"
                                           placeholder="Buscar por nombre...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="categoria">Categoría</label>
                                    <select class="form-control" id="categoria" name="categoria">
                                        <option value="">Todas las categorías</option>
                                        @foreach($SystemConstants::CATEGORIAS_PRODUCTO as $key => $categoria)
                                            <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>
                                                {{ $categoria }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select class="form-control" id="estado" name="estado">
                                        <option value="">Todos los estados</option>
                                        @foreach($SystemConstants::ESTADOS_PRODUCTO as $key => $estado)
                                            <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                                                {{ $estado }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="d-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-undo"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tabla de productos -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Stock</th>
                                    <th>Precio Alquiler</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->id }}</td>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $SystemConstants::getCategoriaProducto($producto->categoria) }}</td>
                                        <td>{{ $producto->stock }}</td>
                                        <td>${{ number_format($producto->precio_alquiler, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $SystemConstants::getColorEstadoProducto($producto->estado) }}">
                                                {{ $SystemConstants::getEstadoProducto($producto->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('productos.show', $producto->id) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('productos.edit', $producto->id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('productos.destroy', $producto->id) }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('¿Está seguro de eliminar este producto?')"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay productos registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-end">
                        {{ $productos->links() }}
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
</style>
@endsection
