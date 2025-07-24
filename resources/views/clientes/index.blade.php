@extends('layouts.app')

@section('content')
<div class="bg-clientes">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #4e73df; color: white; border-radius: 10px 10px 0 0 !important; padding: 1rem 1.5rem;">
                        <h4 class="mb-0" style="font-weight: 600;">Listado de Clientes</h4>
                        <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Cliente
                        </a>
                    </div>
                    <div class="card-body" style="padding: 2rem;">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Ciudad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($clientes as $cliente)
                                        <tr>
                                            <td>{{ $cliente->id }}</td>
                                            <td>{{ $cliente->nombre }}</td>
                                            <td>{{ $cliente->apellido }}</td>
                                            <td>{{ $cliente->email }}</td>
                                            <td>{{ $cliente->telefono }}</td>
                                            <td>{{ $cliente->ciudad }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('clientes.show', $cliente->id) }}"
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('clientes.edit', $cliente->id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('clientes.destroy', $cliente->id) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('¿Está seguro de desactivar este cliente?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No hay clientes registrados</td>
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
</div>
@endsection
