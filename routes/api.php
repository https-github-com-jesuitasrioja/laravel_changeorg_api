<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');

});

Route::get('/peticiones/listado', [\App\Http\Controllers\PeticionesController::class, 'list']);

Route::get('/peticiones/firmar/{id}', [\App\Http\Controllers\PeticionesController::class, 'firmar']);
Route::put('/peticiones/estado/{id}', [\App\Http\Controllers\PeticionesController::class, 'cambiarEstado']);
Route::get('/mispeticiones/', [\App\Http\Controllers\PeticionesController::class, 'listMine']);
Route::get('/users/firmas', [\App\Http\Controllers\UsersController::class, 'peticionesFirmadas']);

Route::resource('peticiones', \App\Http\Controllers\PeticionesController::class);
