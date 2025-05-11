<?php

use App\Controllers\HomeController;
use App\Controller\AuthController;
use Core\Router\Route;

// Authentication
Route::get('/', [HomeController::class, 'index'])->name('root');

Route::get('/', [WelcomeController::class, 'login'])->name('login');

Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::get('/admin', [AdminController::class, 'login'])->name('login');


//rota post do login
//rota post do logout
//rota admin get
//rota usuario normal get
