<?php

namespace Lib\Authentication;

use App\Models\Event;
use App\Models\User;
use App\Models\UserEvent;

class AuthUserEvents
{
    public static function check(int $eventId): bool
    {
        $userId = $_SESSION['user']['id'];
        if (!isset($userId)) {
            return false;
        }
        $pivots = UserEvent::where([
            'event_id' => $eventId,
            'user_id' => $userId
        ]);
        
        if (!$pivots) {
            return false;
        }
        
        return true;
    }
}
