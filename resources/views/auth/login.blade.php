@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow" style="border-radius: 12px;">
                <div class="card-header text-center" style="background: #4e73df; color: #fff; font-size: 1.5rem; font-weight: 600; border-radius: 12px 12px 0 0; letter-spacing: 1px;">
                    <i class="fas fa-user-circle mr-2"></i> Iniciar Sesión
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="email" class="col-form-label">Correo electrónico</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="password" class="col-form-label">Contraseña</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3 d-flex align-items-center">
                            <input class="form-check-input mr-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label mb-0" for="remember">
                                Recordarme
                            </label>
                        </div>
                        <div class="form-group mb-0 text-center">
                            <button type="submit" class="btn btn-primary btn-block" style="font-weight: 600; font-size: 1.1rem;">
                                <i class="fas fa-sign-in-alt mr-1"></i> Ingresar
                            </button>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link mt-2" href="{{ route('password.request') }}" style="color: #4e73df; font-weight: 500;">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
