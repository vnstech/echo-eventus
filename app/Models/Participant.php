<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property string $email
 * @property string $name
 * @property bool @check_in
 * @property bool $check_out
 */

class Participant extends Model
{
    protected static string $table = 'participants';
    protected static array $columns = [
        'event_id',
        'email',
        'name',
        'check_in',
        'check_out'
    ];

    public function validates(): void
    {
        Validations::notEmpty('event_id', $this);
        Validations::notEmpty('email', $this);
    }

    public function __set(string $property, mixed $value): void
    {
        if ($property === 'check_in' || $property === 'check_out') {
            $value = $value ? 1 : 0;
        }
        parent::__set($property, $value);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
