<?php
use Illuminate\Support\Facades\Route;

Route::get('/movies', [\App\Http\Controllers\MovieController::class, 'index']);
Route::get('/movies/{id}', [\App\Http\Controllers\MovieController::class, 'show']);
Route::put('/streaming-providers/{id}', [\App\Http\Controllers\MovieController::class, 'updateProvider']);
Route::post('/movies/{id}/streaming-providers', [\App\Http\Controllers\MovieController::class, 'addProvider']);
Route::delete('/streaming-providers/{id}', [\App\Http\Controllers\MovieController::class, 'deleteProvider']);
