<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrincipalController; 
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ReservaController;

// Página principal (accesible para todos)
Route::get('/', [PrincipalController::class, 'mostrarPrincipal'])->name('principal');

// Login 
Route::post('/', [LoginController::class, 'login'])->middleware('guest')->name('login'); // middleware('guest') permite el acceso únicamente a usuarios que NO están autenticados (evita que visitantes o usuarios no logueados entren)
// Logout
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout'); // middleware('auth') permite el acceso únicamente a usuarios que están autenticados

// Zona privada (solo usuarios logueados)
Route::get('/zonaprivada', [PrincipalController::class, 'mostrarZonaPrivada'])->middleware('auth')->name('zonaprivada');  

// Registro de usuarios
Route::middleware('guest')->group(function () { 

    // Ruta que mostrará el formulario de registro de usuario   
    Route::get('/registro', [UserController::class, 'formRegistro'])->name('formregistro');
    // Crea el usuario  
    Route::post('/registro', [UserController::class, 'guardar'])->name('registro.guardar');
});

// Para modificar o eliminar usuario
Route::middleware('auth')->group(function () {

    // Actualizar datos de usuario
    Route::put('/usuario/actualizar', [UserController::class, 'update'])->name('usuario.update');
    // Dar de baja al usuario
    Route::delete('/usuario/eliminar', [UserController::class, 'destroy'])->name('usuario.destroy');
});

// Gestión de usuarios por el administrador
Route::middleware('admin')->group(function () {

    // Para la listar usuarios por el administrador (Se crea un middleware para ello)
    Route::get('/gestionusuarios', [UserController::class, 'gestionUsuarios'])->name('gestionusuarios');
    // Ruta para acceder a los datos del usuario
    Route::get('/gestionusuarios/{user}', [UserController::class, 'verUsuario'])->name('verusuario');
});

// Página del evento 
Route::get('/evento/{id}', [EventoController::class, 'mostrarEvento'])->name('mostrarevento');

// Registro de eventos, editar y eliminar
Route::middleware('organizadores')->group(function () {

    // Ruta que mostrará el formulario de registro de eventos   
    Route::get('/evento', [EventoController::class, 'formRegistroEvento'])->name('formregistroevento');
    // Crea el evento  
    Route::post('/evento', [EventoController::class, 'guardar'])->name('evento.guardar');
    // Ruta al formulario para actualizar el evento   
    Route::get('/evento/actualizar/{evento}', [EventoController::class, 'formUpdateEvento'])->name('formupdateevento');
    // Actualizar datos del evento
    Route::put('/evento/actualizar/{evento}', [EventoController::class, 'update'])->name('evento.update');
    // Eliminar el evento
    Route::delete('/evento/{evento}', [EventoController::class, 'destroy'])->name('evento.destroy');
    // Para bloquear la reserva de entradas para un evento
    Route::patch('/evento/{evento}/bloquear-reservas', [ReservaController::class, 'bloquearReservas'])->name('evento.bloquearReservas');
});

// Para reservas
Route::middleware('auth')->group(function () {
    // Para  reservas entradas 
    Route::post('/evento/reserva/{evento}', [ReservaController::class, 'reservar'])->name('reservar');
    // Para cancelar una reserva
    Route::delete('/reserva/{reserva}/cancelar', [ReservaController::class, 'cancelarReserva'])->name('reserva.cancelar');
});