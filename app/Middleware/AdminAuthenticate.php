<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\FlashMessage;
use Lib\Authentication\Auth;

class AdminAuthenticate implements Middleware
{
    public function handle(Request $request): void
    {
        if (!Auth::checkAdmin()) {
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
