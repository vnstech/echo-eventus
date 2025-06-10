<?php

namespace Database\Populate;

use App\Models\Event;
use App\Models\User;
use App\Models\UserEvent;

class EventsPopulate
{
    public static function populate(): void
    {
        $userAdmin = User::findByEmail('fulano@example.com');
        $userNormal = User::findByEmail('fulano1@example.com');

        if (!$userAdmin || !$userNormal) {
            echo "No users found. Please check your email.\n";
            return;
        }

        self::createUserExclusiveEvents($userAdmin, 10, 'Admin Event');
        self::createUserExclusiveEvents($userNormal, 10, 'User Event');

        self::createSharedEvents($userAdmin, $userNormal, 10, 'Shared Event');

        echo "Events successfully created\n";
    }

    private static function createUserExclusiveEvents(User $user, int $count, string $baseName): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $event = self::createEvent([
                'name' => "{$baseName} {$i}",
                'owner_id' => $user->id,
            ]);

            self::linkUserToEvent($user->id, $event->id);
        }
    }

    private static function createSharedEvents(User $user1, User $user2, int $count, string $baseName): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $owner = ($i % 2 === 0) ? $user1 : $user2;

            $event = self::createEvent([
                'name' => "{$baseName} {$i}",
                'owner_id' => $owner->id,
            ]);

            self::linkUserToEvent($user1->id, $event->id);
            self::linkUserToEvent($user2->id, $event->id);
        }
    }

    private static function createEvent(array $overrides): Event
    {
        $defaults = [
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'status' => 'upcoming',
            'description' => 'Automatically generated description for tests',
            'location_name' => 'Local Test',
            'address' => 'Example Street 123',
            'category' => null,
            'two_fa_check_attendance' => false
        ];

        $data = array_merge($defaults, $overrides);

        $event = new Event($data);
        $event->save();

        return $event;
    }

    private static function linkUserToEvent(int $userId, int $eventId): void
    {
        $userEvent = new UserEvent([
            'user_id' => $userId,
            'event_id' => $eventId
        ]);
        $userEvent->save();
    }
}
