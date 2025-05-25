<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property string $name
 * @property string $description
 * @property string $start_date
 * @property string $finish_date
 * @property string $location_name
 * @property string $address
 * @property string $category
 * @property bool $2fa_check
 */
class Event extends Model
{
    protected static string $table = 'events';
    protected static array $columns = ['user_id', 'name', 'description', 'start_date', 'finish_date', 'location_name', 'address', 'category', '2fa_check'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function validates(): void
    {
        Validations::notEmpty('user_id', $this);
        Validations::notEmpty('name', $this);
        Validations::notEmpty('start_date', $this);
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password === null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
