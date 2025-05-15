<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthController extends Controller
{
    protected string $layout = 'login';

    public function new(): void
    {
        $title = 'Login Page';
        $this->render('visitor/login/index', compact('title'), 'authentication');
    }

    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        if (!is_array($params)) {
            var_dump($params); 
            echo "Parâmetros inválidos";
            die(); 
        }
        $user = User::findByEmail($params['email']);

        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);

            FlashMessage::success('Login realizado com sucesso!');
            $this->redirectTo(route('events.index'));
        } else {
            FlashMessage::danger('Email e/ou senha inválidos!');
            $this->redirectTo(route('users.login'));
        }
    }

    public function destroy(): void
    {
        Auth::logout();
        FlashMessage::success('Logout realizado com sucesso!');
        $this->redirectTo(route('users.login'));
    }
}