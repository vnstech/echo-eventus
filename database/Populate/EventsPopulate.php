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

        $numberOfevents = 6;

        for ($i = 0; $i < $numberOfevents; $i++) {
            $data = [
                'name' => 'Test Event' . $i, 
                'start_date' => '2025-06-01T09:00',  
                'finish_date' => '2025-06-01T18:00',
                'owner_id' => $userAdmin->id,
                'status' => 'upcoming',
                'description' => 'Description for event test',
                'location_name' => 'TEST',
                'address' => 'Test street 1232',
                'category' => null,
                'two_fa_check_attendance' => false
            ];
            $event = new Event($data);
            $event->save();
            $usersEvents = new UserEvent([
                'user_id' => $userAdmin->id, 
                'event_id' => $event->id
            ]);
            $usersEvents->save();
        }

        $user2 = User::findByEmail('fulano1@example.com');
        $data2 = [
            'name' => 'Test Event', 
            'start_date' => '2025-06-01T09:00',  
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user2->id,
            'status' => 'upcoming',
            'description' => 'Description for event test',
            'location_name' => 'TEST',
            'address' => 'Test street 1232',
            'category' => null,
            'two_fa_check_attendance' => false
        ];

        $event2 = new Event($data2);
        $event2->save();
        $usersEvents2 = new UserEvent([
            'user_id' => $user2->id, 
            'event_id' => $event2->id
        ]);
        $usersEvents2->save();

        echo "Events populated \n";

        $numberOfeventsShared = 5;

        for ($i = 0; $i < $numberOfeventsShared; $i++) {
            $data = [
                'name' => 'Test Event Shared' . $i, 
                'start_date' => '2025-06-01T09:00',  
                'finish_date' => '2025-06-01T18:00',
                'owner_id' => $userAdmin->id,
                'status' => 'upcoming',
                'description' => 'Description for event test',
                'location_name' => 'TEST',
                'address' => 'Test street 1232',
                'category' => null,
                'two_fa_check_attendance' => false
            ];
            $eventShared = new Event($data);
            $eventShared->save();
            if ($i % 2 == 0) { 
                $usersEventsShared = new UserEvent([
                    'user_id' => $userAdmin->id, 
                    'event_id' => $eventShared->id
                ]);
                $usersEventsShared->save();
            } else {
                $usersEventsShared = new UserEvent([
                    'user_id' => $user2->id, 
                    'event_id' => $eventShared->id
                ]);
                $usersEventsShared->save();
            }
        }
    }
}
