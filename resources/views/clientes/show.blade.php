@extends('layouts.app')

@section('content')
<div class="bg-clientes">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #4e73df; color: white; border-radius: 10px 10px 0 0 !important; padding: 1rem 1.5rem;">
                        <h4 class="mb-0" style="font-weight: 600;">Detalles del Cliente</h4>
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                    <div class="card-body" style="padding: 2rem;">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="200">ID</th>
                                        <td>{{ $cliente->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nombre</th>
                                        <td>{{ $cliente->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <th>Apellido</th>
                                        <td>{{ $cliente->apellido }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $cliente->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Teléfono</th>
                                        <td>{{ $cliente->telefono }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dirección</th>
                                        <td>{{ $cliente->direccion }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ciudad</th>
                                        <td>{{ $cliente->ciudad }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estado</th>
                                        <td>{{ $cliente->estado ? 'Activo' : 'Inactivo' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de Creación</th>
                                        <td>{{ $cliente->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Última Actualización</th>
                                        <td>{{ $cliente->updated_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('clientes.destroy', $cliente->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('¿Está seguro de desactivar este cliente?')">
                                    <i class="fas fa-trash"></i> Desactivar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
