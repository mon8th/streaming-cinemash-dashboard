<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('index');
});

// Route::inertia('/about', 'About', ['user' => 'mon']);

Route::inertia('/streaming', 'streaming', ['user' => 'admin']);
Route::inertia('/profile', 'profile', ['user' => 'admin']);
