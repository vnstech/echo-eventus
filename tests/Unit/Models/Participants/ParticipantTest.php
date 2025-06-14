<?php

namespace Tests\Unit\Models\Participants;

use App\Models\Participant;
use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class ParticipantTest extends TestCase
{
    private Participant $participant;
    private Event $event;

    public function setUp(): void
    {
        parent::setUp();

        $user = new User([
        'name' => 'Owner',
        'email' => 'owner@email.com',
        'password' => '123456',
        'password_confirmation' => '123456'
        ]);
        $user->save();

        $this->event = new Event([
            'name' => "Event",
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
        $this->event->save();

        $this->participant = new Participant([
            'event_id' => $this->event->id,
            'name' => 'Participant 1',
            'email' => 'participant1@email.com',
            'check_in' => false,
            'check_out' => false
        ]);
        $this->participant->save();
    }

    public function test_should_create_new_participant(): void
    {
        $participants = Participant::all();
        $this->assertCount(1, $participants);
    }

    public function test_destroy_should_remove_participant(): void
    {
        $this->participant->destroy();
        $this->assertCount(0, Participant::all());
    }

    public function test_update_participant_name(): void
    {
        $newName = 'Updated Participant';
        $this->participant->name = $newName;
        $this->participant->save();

        $updated = Participant::findById($this->participant->id);
        $this->assertEquals($newName, $updated->name);
    }

    public function test_find_by_id_should_return_participant(): void
    {
        $found = Participant::findById($this->participant->id);
        $this->assertEquals($this->participant->name, $found->name);
    }

    public function test_find_by_id_should_return_null(): void
    {
        $this->assertNull(Participant::findById(999999));
    }
}
