<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\DatoProfesionalController;
use App\Http\Controllers\ExperienciaLaboralController;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\DatoIngeniumController;




Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/perfil/foto', [PerfilController::class, 'actualizarFoto']);
    Route::get('/perfil/foto', [PerfilController::class, 'verFoto']);
    Route::apiResource('/docentes', DocenteController::class);

    Route::get('/docentes/{id}/datos-profesionales', [DatoProfesionalController::class, 'index']);
    Route::post('/docentes/{id}/datos-profesionales', [DatoProfesionalController::class, 'store']);
    Route::put('/datos-profesionales/{id}', [DatoProfesionalController::class, 'update']);
    Route::delete('/datos-profesionales/{id}', [DatoProfesionalController::class, 'destroy']);

    Route::get('/docentes/{id}/experiencia-laboral', [ExperienciaLaboralController::class, 'index']);
    Route::post('/docentes/{id}/experiencia-laboral', [ExperienciaLaboralController::class, 'store']);
    Route::put('/experiencia-laboral/{id}', [ExperienciaLaboralController::class, 'update']);
    Route::delete('/experiencia-laboral/{id}', [ExperienciaLaboralController::class, 'destroy']);

    Route::get('/docentes/{id}/capacitaciones', [CapacitacionController::class, 'index']);
    Route::post('/docentes/{id}/capacitaciones', [CapacitacionController::class, 'store']);
    Route::put('/capacitaciones/{id}', [CapacitacionController::class, 'update']);
    Route::delete('/capacitaciones/{id}', [CapacitacionController::class, 'destroy']);

    Route::get('/docentes/{docenteId}/datos-ingenium', [DatoIngeniumController::class, 'index']);
    Route::post('/docentes/{docenteId}/datos-ingenium', [DatoIngeniumController::class, 'store']);
    Route::put('/datos-ingenium/{id}', [DatoIngeniumController::class, 'update']);
    Route::delete('/datos-ingenium/{id}', [DatoIngeniumController::class, 'destroy']);

    Route::get('/docentes/{id}/completo', [DocenteController::class, 'obtenerDocenteCompleto']);
    Route::get('/docentes-completos', [DocenteController::class, 'obtenerDocentesCompletos']);

    Route::get('/docentes/archivo/{tipo}/{filename}', [DocenteController::class, 'verArchivo']);

    Route::post('/registro-docente', [DocenteController::class, 'storeCombinado']);
    
    Route::group(['middleware' => 'admin'], function() {
        Route::apiResource('/usuarios', UsuarioController::class);



    });
});
