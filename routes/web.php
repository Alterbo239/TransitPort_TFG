<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GruaController;
use App\Http\Controllers\ZonaController;
use App\Http\Controllers\PatioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\BuqueController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

Route::view('/loginCliente', 'Client.LoginCliente') -> name('loginCliente');


Route::get('/registrar', [UsuarioController::class, 'registro']) -> name('registrar');

Route::view('/registrar_P1', 'Client.Registro.Registro_p1') -> name('registrar_P1');
Route::post('/registrar_P2', [UsuarioController::class, 'validarRegistro1']) -> name('registrar_P2');

Route::post('/registroFinal', [UsuarioController::class, 'subirRegistro']) -> name('registroFinal');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//una vez autentificado mediante auth
Route::middleware('auth')->group(function () {

    Route::view('/exito', 'Administrativo.exito') -> name('exito');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //dependiendo del rol del usuario accede a unas funciones u otras
    Route::middleware(['gestor'])->group(function () {

        Route::get('/crearUsuario', [GestorController::class, 'crearUsuario'])->name('crearUsuario');
        Route::post('/guardarUsuario', [GestorController::class, 'guardarUsuario'])->name('guardarUsuario');

        Route::view('/verEmpresa', 'Gestor.verEmpresa')->name('verEmpresa');
        Route::get('/verEmpresas', [EmpresaController::class, 'visualizarEmpresas'])->name('verEmpresas');
        Route::post('/actualizarEmpresa', [EmpresaController::class, 'update'])->name('actualizarEmpresa');
        Route::get('/getCiudades', [EmpresaController::class, 'getCiudades'])->name('getCiudades');

        Route::view('/crearEmpresa', 'Gestor.crearEmpresa')->name('crearEmpresa');
        Route::post('/guardarEmpresa', [EmpresaController::class, 'store'])->name('guardarEmpresa');

        Route::get('/crearPatio', [PatioController::class, 'crearPatio'])->name('crearPatio');
        Route::get('/crearGrua', [GruaController::class, 'crearGrua'])->name('crearGrua');
        Route::post('/guardarGrua', [GruaController::class, 'guardarGrua'])->name('guardarGrua');
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/guardarPatio', [PatioController::class, 'guardarPatio'])->name('guardarPatio');
        Route::post('/guardarZona', [ZonaController::class, 'guardarZona'])->name('guardarZona');
        Route::get('/verZona', [ZonaController::class, 'verZona'])->name('verZona');

    });

    Route::middleware(['administrativo'])->group(function () {

        Route::get('/crearOrden', [OrdenController::class, 'crearOpciones'])->name('crearOrden');
        Route::post('/guardarOrden', [OrdenController::class, 'guardarOrden'])->name('guardarOrden');
        Route::view('/crearTurno', 'Administrativo.crearTurno')->name('crearTurno');
        Route::post('/guardarTurno', [TurnoController::class, 'guardarTurno'])->name('guardarTurno');
        Route::view('/calendario', 'Administrativo.calendario')->name('calendario');

        Route::get('/verTransporte', [BuqueController::class, 'getEmpresas'])->name('verTransporte');
        Route::get('/verTransportes', [BuqueController::class, 'visualizarTransportes'])->name('verTransportes');
        Route::post('/actualizarTransporte', [BuqueController::class, 'update'])->name('actualizarTransporte');

        Route::get('/asignarTurno', [TurnoController::class, 'crearOpciones'])->name('asignarTurno');
        Route::post('/actualziarTurno', [TurnoController::class, 'actualizarTurno']) -> name('actualizarTurno');


        Route::view('/verAuditoria', 'Administrativo.Auditorias.verAuditoria') -> name('verAuditoria');
        Route::get('/recogerAuditoria', [OrdenController::class, 'visualizarAuditoria']) -> name('recogerAuditoria');
        Route::get('/verAuditoria/{id}', [OrdenController::class, 'mostrarUno']) -> name('mostrarAuditoria');

        Route::get('/registrarTransporte', [EmpresaController::class, 'getEmpresas']) -> name('registrarTransporte');
        Route::post('/storeVehiculo', [BuqueController::class, 'storeVehiculo']) -> name('storeVehiculo');

        Route::get('/gestionarCitas', [ZonaController::class, 'getZonas']) -> name('gestionarCitas');
        Route::get('/storeCitas', [CitaController::class, 'sotreCitas']) -> name('storeCitas');
    });

    Route::middleware(['operador'])->group(function () {
        Route::get('operador/ordenes', [OrdenController::class, 'index'])->name('ordenes');
        Route::get('operador/perfil', [OperadorController::class, 'perfil'])->name('perfil');
        Route::get('operador/verNotificaciones', [OperadorController::class, 'verNotificaciones'])->name('verNotificaciones');
        Route::post('operador/logout', [AuthController::class, 'logout'])->name('operador.logout');
        Route::post('operador/logout', [AuthController::class, 'volver'])->name('operador.volver');

    });

    Route::middleware(['cliente'])->group(function () {
        Route::get('/pedirCitas', [CitaController::class, 'getBuques']) -> name('pedirCitas');
        Route::post('/storePedirCitas', [CitaController::class, 'storePedida']) -> name('storePedirCitas');

        Route::get('/registrarVehiculo', [EmpresaController::class, 'getEmpresas']) -> name('registrarVehiculo');

        Route::view('/exitoCliente', 'Client/Pantallas.exito') -> name('exitoCliente');
    });

});

require __DIR__.'/auth.php';
