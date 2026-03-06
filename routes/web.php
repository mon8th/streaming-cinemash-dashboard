<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']);

// Route::inertia('/about', 'About', ['user' => 'mon']);

Route::inertia('/streaming', 'streaming', ['user' => 'admin']);
Route::inertia('/profile', 'profile', ['user' => 'admin']);
