<?php

namespace Tests\Acceptance\Event;

use App\Models\User;
use App\Models\Event;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;
use App\Models\UserEvent;
use App\Models\Participant;

class EventCest extends BaseAcceptanceCest
{
    public function seeEvent(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
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
            'two_fa_check_attendance' => false,
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
            'password_confirmation' => '123456',
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
                'two_fa_check_attendance' => false,
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
            'password_confirmation' => '123456',
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
            'two_fa_check_attendance' => false,
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
            'password_confirmation' => '123456',
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
            'two_fa_check_attendance' => false,
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
        $page->executeJS("document.querySelector('button[type=submit]').click();");
        $page->waitForElementNotVisible('form', 5); // opcional, ajuda com redireção
        $page->seeCurrentUrlEquals('/events');
        $page->see($newName);
    }

    public function updateEventUnsuccessfully(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
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
            'two_fa_check_attendance' => false,
        ]);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $page->login($user->email, $user->password);
        $page->amOnPage("/events/{$event->id}/edit");

        $page->executeJS("document.querySelector('input[name=name]').value = '';");

        $page->see('Edit Event');
        $page->executeJS("document.querySelector('button[type=submit]').click();");

        $page->see($event->name);
    }

    public function deleteEventSuccessfully(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
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
            'two_fa_check_attendance' => false,
        ]);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $page->login($user->email, $user->password);
        $page->amOnPage("/events/{$event->id}");

        $page->executeJS("document.querySelector('button[data-bs-target=\"#deleteModal\"]').click();");

        $page->waitForElementVisible('#deleteModal form button[type=submit]', 5);
        $page->waitForElementClickable('#deleteModal form button[type=submit]', 5);

        $page->click('#deleteModal form button[type=submit]');
        $page->waitForElementNotVisible('#deleteModal', 5);
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
            'two_fa_check_attendance' => false,
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
            'password_confirmation' => '123456',
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
            'two_fa_check_attendance' => false,
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

    public function seeRelationParticipantEvent(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'user@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);
        $user->save();

        $event = new Event([
            'name' => "Event With Participant",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user->id,
            'status' => 'upcoming',
            'description' => 'Event for participant relation test',
            'location_name' => 'Test Location',
            'address' => 'Test Address',
            'category' => '',
            'two_fa_check_attendance' => false,
        ]);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $participant = new Participant([
            'event_id' => $event->id,
            'name' => 'Participant 1',
            'email' => 'participant1@example.com',
            'check_in' => false,
            'check_out' => false,
        ]);
        $participant->save();

        $page->login($user->email, $user->password);
        $page->amOnPage("/events/{$event->id}/participants");

        $page->see($participant->name);
        $page->see($participant->email);
    }

    public function removeRelationParticipantEvent(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'user@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);
        $user->save();

        $event = new Event([
            'name' => "Event With Participant",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user->id,
            'status' => 'upcoming',
            'description' => 'Event for participant relation test',
            'location_name' => 'Test Location',
            'address' => 'Test Address',
            'category' => '',
            'two_fa_check_attendance' => false,
        ]);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $participant = new Participant([
            'event_id' => $event->id,
            'name' => 'Participant 1',
            'email' => 'participant1@example.com',
            'check_in' => false,
            'check_out' => false,
        ]);
        $participant->save();

        $page->login($user->email, $user->password);
        $page->amOnPage("/events/{$event->id}/participants");
        $page->click('button[data-bs-target="#deleteModal-' . $participant->id . '"]');
        $page->waitForElementVisible('#deleteModal-' . $participant->id);
        $page->click('#deleteModal-' . $participant->id . ' form button[type=submit]');
        $page->dontSee($participant->name);
        $page->dontSee($participant->email);
    }

    public function testUploadAvatarEvent(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);
        $user->save();

        $event = new Event([
            'name' => "Event With Avatar",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user->id,
            'status' => 'upcoming',
            'description' => 'Event image test',
            'location_name' => 'Test Location',
            'address' => 'Test Address',
            'category' => '',
            'two_fa_check_attendance' => false,
        ]);
        $event->save();

        $pivot = new UserEvent([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $pivot->save();

        $page->login($user->email, $user->password);
        $page->amOnPage("/events/{$event->id}/edit");

        $page->seeElement('#eventAvatar');
        $page->attachFile('#eventAvatar', 'avatar_test.png');

        $page->executeJS('document.querySelector("#submitEventAvatar").click()');
        $page->see('Event updated successfully!');
    }
}
