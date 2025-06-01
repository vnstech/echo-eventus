<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;

class HomeController extends Controller
{
    public function index(): void
    {
        if (Auth::check()) {
            $this->redirectTo(route('events.index'));
        } else {
            $title = 'Home Page';
            $this->render('visitor/home/index', compact('title'));
        }
    }
}
