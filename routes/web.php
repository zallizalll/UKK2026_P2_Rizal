<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('layout.home'); // karena ada di resources/views/layout/home.blade.php
})->name('dashboard');