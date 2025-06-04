<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/perfil/archivo/{tipo}/{filename}', [ProfileController::class, 'verArchivo']);
    Route::post('/perfil', [ProfileController::class, 'store']);
    Route::put('/perfil', [ProfileController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

   
    Route::group(['middleware' => 'admin'], function() {
        Route::apiResource('teachers', TeacherController::class);
        Route::get('/docentes/archivo/{tipo}/{filename}', [TeacherController::class, 'verArchivo']);

    });
});
