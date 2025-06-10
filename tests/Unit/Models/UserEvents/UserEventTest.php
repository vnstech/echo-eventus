<?php

namespace Tests\Unit\Models\UserEvents;

use App\Models\UserEvent;
use Tests\TestCase;
use App\Models\Event;
use App\Models\User;

class UserEventTest extends TestCase
{
    private UserEvent $userEvent;
    private int $userId;
    private int $eventId;

    public function setUp(): void
    {
        parent::setUp();

        $user1 = new User([
            'name' => 'User Test',
            'email' => 'user1@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);
        $user1->save();

        $user2 = new User([
            'name' => 'User Test',
            'email' => 'user2@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);
        $user2->save();

        $event1 = new Event([
            'name' => "Event Name",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $user1->id,
            'status' => 'upcoming',
            'description' => 'Test description',
            'location_name' => 'Test location',
            'address' => 'Test street',
            'category' => '',
            'two_fa_check_attendance' => false
        ]);
        $event1->save();

        $this->userId = $user2->id;
        $this->eventId = $event1->id;

        $this->userEvent = new UserEvent([
            'user_id' => $this->userId,
            'event_id' => $this->eventId,
        ]);
        $saved = $this->userEvent->save();

        if (!$saved) {
            $this->fail('Failed to save user event in setUp.');
        }
    }

    public function test_should_create_new_event(): void
    {
        $events = UserEvent::all();
        $this->assertCount(1, $events);
    }

    public function test_destroy_should_remove_event(): void
    {
        $this->userEvent->destroy();

        $this->assertCount(0, UserEvent::all());
    }
}
