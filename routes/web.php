<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\RetiroController;

// Rutas de autenticación
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rutas de registro
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rutas de restablecimiento de contraseña
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Rutas de la aplicación
Route::get('/', function () {
    return redirect()->route('home');
});

// La ruta home existente se mantiene igual
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Grupo de rutas autenticadas
Route::middleware(['auth'])->group(function () {
    // Productos
    Route::get('/productos/search', [ProductoController::class, 'search'])->name('productos.search');
    Route::resource('productos', ProductoController::class);

    // Reservas
    Route::resource('reservas', ReservaController::class);
    Route::put('/reservas/{reserva}/confirmar', [ReservaController::class, 'confirmar'])->name('reservas.confirmar');
    Route::put('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    Route::delete('/reservas/{reserva}/detalles/{detalle}', [ReservaController::class, 'eliminarDetalle'])
        ->name('reservas.eliminar-detalle');
    Route::get('/reservas/{reserva}/ver-pdf', [ReservaController::class, 'verPdf'])->name('reservas.ver-pdf');

    // Clientes
    Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
    Route::resource('clientes', ClienteController::class);

    // Entregas
    Route::get('/entregas', [EntregaController::class, 'index'])->name('entregas.index');
    Route::post('/entregas/{reserva}/entregar', [EntregaController::class, 'entregar'])->name('entregas.entregar');
    Route::post('/entregas/{reserva}/retirar', [EntregaController::class, 'retirar'])->name('entregas.retirar');
    Route::get('/entregas/exportar-pdf', [EntregaController::class, 'exportarPdf'])->name('entregas.exportar-pdf');
    Route::get('/entregas/ver-pdf', [EntregaController::class, 'verPdf'])->name('entregas.ver-pdf');

    // Retiros
    Route::get('/retiros', [RetiroController::class, 'index'])->name('retiros.index');
    Route::get('/retiros/{reserva}', [RetiroController::class, 'show'])->name('retiros.show');
    Route::post('/retiros/{reserva}/procesar', [RetiroController::class, 'procesarRetiro'])->name('retiros.procesar');
});
