<?php

namespace Tests\Acceptance\Authentication;

use App\Models\User;
use App\Models\Event;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class EventCest extends BaseAcceptanceCest
{
    public function createSuccesfully(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $event = new Event([
            'name' => 'Test Event',
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'user_id' => $user->id,
            'status' => 'upcoming',
            'description' => 'Description for event test',
            'location_name' => 'TEST',
            'address' => 'Test street 1232',
            'category' => null,
            'two_fa_check_attendance' => false
        ]);

        $event->save();

        $page->login($user->email, $user->password);

        $page->amOnPage('/events');
        $page->see($event->name);
    }

}
