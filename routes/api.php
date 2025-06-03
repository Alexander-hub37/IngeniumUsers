<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TeacherController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('spaces', [SpaceController::class, 'index']);
Route::get('spaces/{id}', [SpaceController::class, 'show']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('reservations', [ReservationController::class, 'index']);
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::get('reservations/{id}', [ReservationController::class, 'show']);
    Route::put('reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('reservations/{id}', [ReservationController::class, 'destroy']);
    Route::group(['middleware' => 'admin'], function() {
        Route::apiResource('teachers', TeacherController::class);
        Route::get('/docentes/archivo/{tipo}/{filename}', [TeacherController::class, 'verArchivo']);
        Route::post('spaces', [SpaceController::class, 'store']);  
        Route::put('spaces/{id}', [SpaceController::class, 'update']);
        Route::delete('spaces/{id}', [SpaceController::class, 'destroy']);
    });
});
