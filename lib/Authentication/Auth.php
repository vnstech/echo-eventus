<?php

namespace Lib\Authentication;

use App\Models\User;

class Auth
{
    public static function login($user): void
    {
        $_SESSION['user']['id'] = $user->id;
    }

    public static function user(): ?User
    {
        if (isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            return User::findById($id);
        }

        return null;
    }

    public static function checkAdmin(): bool
    {
        $userId = $_SESSION["user"]["id"] ?? null;

        if (isset($userId)) {
            $id = $_SESSION['user']['id'];
            return User::isAdminById($id);
        }
        return false;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']['id']) && self::user() !== null;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']['id']);
    }
}
