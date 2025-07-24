@extends('layouts.app')

@section('content')
<div class="bg-clientes">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow" style="border-radius: 10px;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #4e73df; color: white; border-radius: 10px 10px 0 0 !important; padding: 1rem 1.5rem;">
                        <h4 class="mb-0" style="font-weight: 600;">Editar Cliente</h4>
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                    <div class="card-body" style="padding: 2rem;">
                        <form method="POST" action="{{ route('clientes.update', $cliente->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="nombre" class="col-md-4 col-form-label text-md-right">Nombre</label>
                                <div class="col-md-6">
                                    <input id="nombre" type="text"
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="apellido" class="col-md-4 col-form-label text-md-right">Apellido</label>
                                <div class="col-md-6">
                                    <input id="apellido" type="text"
                                           class="form-control @error('apellido') is-invalid @enderror"
                                           name="apellido" value="{{ old('apellido', $cliente->apellido) }}" required>
                                    @error('apellido')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email', $cliente->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="telefono" class="col-md-4 col-form-label text-md-right">Teléfono</label>
                                <div class="col-md-6">
                                    <input id="telefono" type="text"
                                           class="form-control @error('telefono') is-invalid @enderror"
                                           name="telefono" value="{{ old('telefono', $cliente->telefono) }}" required>
                                    @error('telefono')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="direccion" class="col-md-4 col-form-label text-md-right">Dirección</label>
                                <div class="col-md-6">
                                    <input id="direccion" type="text"
                                           class="form-control @error('direccion') is-invalid @enderror"
                                           name="direccion" value="{{ old('direccion', $cliente->direccion) }}" required>
                                    @error('direccion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="ciudad" class="col-md-4 col-form-label text-md-right">Ciudad</label>
                                <div class="col-md-6">
                                    <input id="ciudad" type="text"
                                           class="form-control @error('ciudad') is-invalid @enderror"
                                           name="ciudad" value="{{ old('ciudad', $cliente->ciudad) }}" required>
                                    @error('ciudad')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Actualizar Cliente
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
