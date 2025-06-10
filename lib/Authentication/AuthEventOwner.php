<?php

namespace Lib\Authentication;

use App\Models\Event;

class AuthEventOwner
{
    public static function check(int $eventId): bool
    {
        $event = Event::findById($eventId);

        $userId = $_SESSION['user']['id'];
        if ($event->owner_id != $userId) {
            return false;
        }

        return true;
    }
}
