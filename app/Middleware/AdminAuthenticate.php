<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;
use Models\User;

class AdminAuthenticate implements Middleware
{
    public function handle(Request $request): void
    {
        if (!User::getIsAdmin()) {
            FlashMessage::danger('You do not have permission to access this page.');
            $this->redirectTo(route('users.login'));
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
