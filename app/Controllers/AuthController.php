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
        $user = User::findByEmail($params['email']);

        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);

            FlashMessage::success('Login successful!');
            $this->redirectTo(route('events.index'));
        } else {
            FlashMessage::danger('Invalid email and/or password!');
            $this->redirectTo(route('users.login'));
        }
    }

    public function destroy(): void
    {
        Auth::logout();
        FlashMessage::success('Logout successful!');
        $this->redirectTo(route('users.login'));
    }
}
