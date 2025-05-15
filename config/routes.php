<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\EventsController;
use App\Controllers\AdminController;
use Core\Router\Route;

// Authentication
Route::get('/', [HomeController::class, 'index'])->name('root');

Route::get('/login', [AuthController::class, 'new'])->name('users.login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('users.authenticate');

Route::get('/events', [EventsController::class, 'index'])->name('events.index');

Route::get('/admin', [AdminController::class, 'index'])->name('admin');
