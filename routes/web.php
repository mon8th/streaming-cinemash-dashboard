<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Streaming/index');
});

// Route::inertia('/about', 'About', ['user' => 'mon']);

