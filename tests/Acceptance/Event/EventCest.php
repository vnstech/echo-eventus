<?php

namespace Tests\Acceptance\Event;

use App\Models\User;
use App\Models\Event;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;
use App\Models\UserEvent;

class EventCest extends BaseAcceptanceCest
{
    public function seeEvent(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $event = new Event([
            'name' => "Test Event",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user->id,
            'status' => 'upcoming',
            'description' => 'Description for event test',
            'location_name' => 'TEST',
            'address' => 'Test street 1232',
            'category' => '',
            'two_fa_check_attendance' => false
        ]);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $page->login($user->email, $user->password);
        $page->amOnPage('/events');

        $page->see($event->name);
    }

    public function seeMultipleEventsPaginated(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $events = [];
        for ($i = 1; $i <= 40; $i++) {
            $event = new Event([
                'name' => "Test Event {$i}",
                'start_date' => '2025-06-01T09:00',
                'finish_date' => '2025-06-01T18:00',
                'owner_id' => $user->id,
                'status' => 'upcoming',
                'description' => "Description for event test {$i}",
                'location_name' => "TEST Location {$i}",
                'address' => "Test street 123{$i}",
                'category' => '',
                'two_fa_check_attendance' => false
            ]);
            $event->save();
            $pivot = new UserEvent([
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);
            $pivot->save();
            $events[] = $event;
        }

        $page->login($user->email, $user->password);

        $perPage = 12;
        $totalPages = ceil(count($events) / $perPage);

        for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++) {
            $page->amOnPage("/events?page={$currentPage}");

            $start = ($currentPage - 1) * $perPage;
            $end = min($start + $perPage, count($events));

            for ($i = $start; $i < $end; $i++) {
                $page->see($events[$i]->name);
            }
        }
    }

    public function notSeeEvent(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $eventData = [
            'name' => null, // invalid
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user->id,
            'status' => 'upcoming',
            'description' => 'Description for event test',
            'location_name' => 'TEST',
            'address' => 'Test street 1232',
            'category' => '',
            'two_fa_check_attendance' => false
        ];

        $event = new Event($eventData);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $page->login($user->email, $user->password);
        $page->amOnPage('/events');

        $page->dontSee($eventData["description"]);
    }

    public function updateEventSuccessfully(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $event = new Event([
            'name' => "Original Event Name",
            'start_date' => '2025-06-1T09:00',
            'finish_date' => '2025-06-1T18:00',
            'owner_id' => $user->id,
            'status' => 'upcoming',
            'description' => 'Test description',
            'location_name' => 'Test location',
            'address' => 'Test street',
            'category' => '',
            'two_fa_check_attendance' => false
        ]);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $page->login($user->email, $user->password);
        $page->amOnPage("/events/{$event->id}/edit");

        $newName = "Updated Event Name";
        $page->fillField('name', $newName);
        $page->click('Update Event');

        $page->seeCurrentUrlEquals('/events');
        $page->see($newName);
    }

    public function updateEventUnsuccessfully(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $event = new Event([
            'name' => "Original Event Name",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user->id,
            'status' => 'upcoming',
            'description' => 'Test description',
            'location_name' => 'Test location',
            'address' => 'Test street',
            'category' => '',
            'two_fa_check_attendance' => false
        ]);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $page->login($user->email, $user->password);
        $page->amOnPage("/events/{$event->id}/edit");

        $page->fillField('name', $event->name);
        $page->click('Update Event');

        $page->see('Error updating event. Please check the data!');
        $page->seeCurrentUrlEquals("/events/{$event->id}");
    }

    public function deleteEventSuccessfully(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $event = new Event([
            'name' => "Event To Be Deleted",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user->id,
            'status' => 'upcoming',
            'description' => 'This event will be deleted in the test',
            'location_name' => 'Test Location',
            'address' => 'Test Address',
            'category' => '',
            'two_fa_check_attendance' => false
        ]);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $page->login($user->email, $user->password);
        $page->amOnPage("/events/{$event->id}");
        $page->click('button[data-bs-target="#deleteModal"]');
        $page->waitForElementVisible('#deleteModal form button[type=submit]', 2);
        $page->click('#deleteModal form button[type=submit]');

        $page->seeCurrentUrlEquals('/events');
        $page->dontSee($event->name);
    }

    public function seeRelationUsersEvents(AcceptanceTester $page): void
    {
        $user1 = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user1->save();

        $user2 = new User([
            'name' => 'User 2',
            'email' => 'fulano1@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user2->save();

        $event = new Event([
            'name' => "Event To Be Deleted",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user1->id,
            'status' => 'upcoming',
            'description' => 'This event will be deleted in the test',
            'location_name' => 'Test Location',
            'address' => 'Test Address',
            'category' => '',
            'two_fa_check_attendance' => false
        ]);
        $event->save();

        $pivot1 = new UserEvent([
            'user_id' => $user1->id,
            'event_id' => $event->id,
        ]);
        $pivot1->save();

        $pivot2 = new UserEvent([
            'user_id' => $user2->id,
            'event_id' => $event->id,
        ]);
        $pivot2->save();
        $page->login($user1->email, $user1->password);
        $page->amOnPage("/events");
        $page->see($event->name);
        $page->click('Logout');
        $page->login($user2->email, $user2->password);
        $page->amOnPage("/events");
        $page->see($event->name);
    }

    public function removeRelationUsersEvents(AcceptanceTester $page): void
    {
        $user1 = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user1->save();

        $user2 = new User([
            'name' => 'User 2',
            'email' => 'fulano1@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user2->save();

        $event = new Event([
            'name' => "Event To Be Deleted",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user1->id,
            'status' => 'upcoming',
            'description' => 'This event will be deleted in the test',
            'location_name' => 'Test Location',
            'address' => 'Test Address',
            'category' => '',
            'two_fa_check_attendance' => false
        ]);
        $event->save();

        $pivot1 = new UserEvent([
            'user_id' => $user1->id,
            'event_id' => $event->id,
        ]);
        $pivot1->save();

        $pivot2 = new UserEvent([
            'user_id' => $user2->id,
            'event_id' => $event->id,
        ]);

        $pivot2->save();
        $page->login($user1->email, $user1->password);

        $page->amOnPage("/events/{$event->id}/members");
        $page->click('button[data-bs-target="#deleteModal-' . $user2->id . '"]');
        $page->waitForElementVisible('#deleteModal-' . $user2->id);
        $page->click('#deleteModal-' . $user2->id . ' form button[type=submit]');
        $page->dontSee($user2->name);
        $page->click('Logout');

        $page->login($user2->email, $user2->password);
        $page->amOnPage("/events");
        $page->dontSee($event->name);
    }
}
