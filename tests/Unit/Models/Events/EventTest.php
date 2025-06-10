<?php

namespace Tests\Unit\Models\Events;

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class EventTest extends TestCase
{
    private User $user;
    private ?Event $event;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);
        $this->user->save();

        $this->event = new Event([
            'name' => "Event Name",
            'start_date' => '2025-06-01T09:00',
            'finish_date' => '2025-06-01T18:00',
            'owner_id' => $this->user->id,
            'status' => 'upcoming',
            'description' => 'Test description',
            'location_name' => 'Test location',
            'address' => 'Test street',
            'category' => '',
            'two_fa_check_attendance' => false
        ]);
        $saved = $this->event->save();

        if (!$saved) {
            $this->event = null;
            $this->fail('Failed to save event in setUp.');
        }
    }

    public function test_should_create_new_event(): void
    {
        $events = Event::all();
        $this->assertCount(1, $events);
    }

    public function test_destroy_should_remove_event(): void
    {
        $this->assertNotNull($this->event);
        $this->event->destroy();

        $this->assertCount(0, Event::all());
    }

    public function test_update_event_name(): void
    {
        $this->assertNotNull($this->event);
        $newName = 'Updated Event Name';
        $this->event->name = $newName;
        $this->event->save();

        $updatedEvent = Event::findById($this->event->id);
        $this->assertEquals($newName, $updatedEvent->name);
    }

    public function test_find_by_id_should_return_event(): void
    {
        $this->assertNotNull($this->event);
        $found = Event::findById($this->event->id);
        $this->assertEquals($this->event->name, $found->name);
    }

    public function test_find_by_id_should_return_null(): void
    {
        $this->assertNull(Event::findById(999999));
    }
}
