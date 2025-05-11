<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(): void
    {
        $title = 'Login Page';
        $this->render('visitor/login/index', compact('title'), 'authentication');
    }
}
