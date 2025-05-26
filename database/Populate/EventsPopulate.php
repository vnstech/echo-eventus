<?php

namespace Database\Populate;

use App\Models\Event;
use App\Models\User;

class EventsPopulate
{
    public static function populate(): void
    {
        $userAdmin = User::findByEmail('fulano@example.com');

        $numberOfevents = 4;

        for ($i = 0; $i < $numberOfevents; $i++) {
          $data = [
            'name' => 'Test Event' . $i, 
            'start_date' => '2025-06-01T09:00',  
            'finish_date' => '2025-06-01T18:00',
            'user_id' => $userAdmin->id,
            'status' => 'upcoming',
            'description' => 'Description for event test',
            'location_name' => 'TEST',
            'address' => 'Test street 1232',
            'category' => null,
            'two_fa_check_attendance' => false
          ];
          $event = new Event($data);
          $event->save();
        }

        $user2 = User::findByEmail('fulano1@example.com');
        $data = [
            'name' => 'Test Event' . $i, 
            'start_date' => '2025-06-01T09:00',  
            'finish_date' => '2025-06-01T18:00',
            'user_id' => $user2->id,
            'status' => 'upcoming',
            'description' => 'Description for event test',
            'location_name' => 'TEST',
            'address' => 'Test street 1232',
            'category' => null,
            'two_fa_check_attendance' => false
          ];

        $event2 = new Event($data);
        $event2->save();

        echo "Events populated with $numberOfevents registers\n";
    }
}