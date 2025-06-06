<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\Model;
use Lib\Validations;

/**
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 *
 *
 * */

class UserEvent extends Model
{
    protected static string $table = 'users_events';
    protected static array $columns = [
        'user_id',
        'event_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function validates(): void
    {
        if (!Validations::notEmpty('user_id', $this)) {
            return;
        }

        if (!Validations::notEmpty('event_id', $this)) {
            return;
        }

        if (!user::findById($this->user_id)) {
            $this->addError('user_id', 'does not exist!');
            return;
        }

        if (!event::findById($this->event_id)) {
            $this->addError('event_id', 'does not exist!');
            return;
        }
    }

    public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute] = "{$attribute} {$message}";
    }

    /**
    *@return string[] List of error messages, each as a string.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}