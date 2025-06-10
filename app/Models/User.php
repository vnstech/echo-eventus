<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\HasMany;
use App\Models\Event;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 * @property string $avatar_name
 * @property bool $is_admin
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['name', 'email', 'encrypted_password', 'avatar_name', 'is_admin'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('email', $this);

        Validations::uniqueness('email', $this);

        if ($this->newRecord()) {
            Validations::passwordConfirmation($this);
        }
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password === null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public static function findByEmail(string $email): ?User
    {
        return self::findBy(['email' => $email]);
    }

    public static function isAdminById(int $userId): bool
    {
        $user = self::findById($userId);
        return $user !== null && (bool)$user->is_admin;
    }

    public function __set(string $property, mixed $value): void
    {
        if ($property === 'is_admin') {
            $value = $value ? 1 : 0;
        }

        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    public function usersEvents(): HasMany
    {
        return $this->hasMany(UserEvent::class, 'user_id');
    }
}
