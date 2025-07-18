<?php

namespace App\Models;

use App\Services\EventAvatar;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\HasMany;

/**
 * @property int $id
 * @property int $owner_id
 * @property string $status
 * @property string $name
 * @property string $description
 * @property string $start_date
 * @property string $finish_date
 * @property string $location_name
 * @property string $address
 * @property string $category
 * @property bool $two_fa_check_attendance
 * @property string|null $avatar_name
 */
class Event extends Model
{
    protected static string $table = 'events';
    protected static array $columns = [
        'owner_id',
        'status',
        'name',
        'description',
        'start_date',
        'finish_date',
        'location_name',
        'address',
        'category',
        'two_fa_check_attendance',
        'avatar_name',
    ];

    public function validates(): void
    {
        Validations::notEmpty('owner_id', $this);
        Validations::notEmpty('name', $this);
        Validations::notEmpty('start_date', $this);
    }

    public function __set(string $property, mixed $value): void
    {
        if ($property === 'two_fa_check_attendance') {
            $value = $value ? 1 : 0;
        }
        if ($property === 'category') {
            $value = $value ? $value : null;
        }
        if ($property === 'status') {
            $value = "upcoming";
        }
        parent::__set($property, $value);
    }

    public function usersEvents(): HasMany
    {
        return $this->hasMany(UserEvent::class, 'event_id');
    }

    public function avatar(): EventAvatar
    {
        return new EventAvatar($this, [
            'extension' => ['png', 'jpg', 'jpeg'],
            'size' => (string)(2 * 1024 * 1024),
        ]);
    }
}
