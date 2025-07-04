<?php

namespace Tests\Unit\Service;

use App\Models\Event;
use App\Models\User;
use App\Services\EventAvatar;
use Tests\TestCase;

class EventAvatarServiceTest extends TestCase
{
    private EventAvatar $eventAvatar;
    private User $user;
    private Event $event;

    /** @var array<string, mixed> */
    private array $image;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpEvent();

        $tmpFile = tempnam(sys_get_temp_dir(), 'php');
        file_put_contents($tmpFile, 'fake image content');

        $this->image = [
            'name' => 'avatar_test.png',
            'full_path' => 'avatar_test.png',
            'type' => 'image/png',
            'tmp_name' => $tmpFile,
            'error' => 0,
            'size' => filesize($tmpFile),
        ];

        $this->eventAvatar = new EventAvatar($this->event, [
            'extension' => ['jpg', 'jpeg', 'png'],
            'size' => 2 * 1024 * 1024,
        ]);
    }

    public function setUpEvent(): void
    {
        $this->user = new User([
            'name' => 'Event User',
            'email' => 'eventuser@example.com',
            'password' => '123456'
        ]);
        $this->user->save();

        $this->event = new Event([
            'name' => 'Event Test',
            'owner_id' => $this->user->id,
            'start_date' => '2025-06-01 09:00:00',
            'finish_date' => '2025-06-01 18:00:00',
            'status' => 'upcoming',
            'description' => 'Description',
            'location_name' => 'Local',
            'address' => 'Address',
            'category' => '',
        ]);
        $this->event->save();
    }

    public function testUploadValidImage(): void
    {
        $avatar = $this->getMockBuilder(EventAvatar::class)
            ->setConstructorArgs([$this->event, [
                'extension' => ['jpg', 'jpeg', 'png'],
                'size' => 2 * 1024 * 1024,
            ]])
            ->onlyMethods(['updateFile'])
            ->getMock();

        $avatar->expects($this->once())
            ->method('updateFile')
            ->willReturn(true);

        $result = $avatar->update($this->image);
        $this->assertTrue($result);
    }

    public function testInvalidExtension(): void
    {
        $this->image['name'] = 'invalid_file.txt';
        $result = $this->eventAvatar->update($this->image);
        $this->assertFalse($result);
        $this->assertEquals('Image format not supported!', $this->event->errors('avatar_name'));
    }

    public function testInvalidSize(): void
    {
        $this->image['size'] = 3 * 1024 * 1024;

        $result = $this->eventAvatar->update($this->image);
        $this->assertFalse($result);
        $this->assertEquals('The file must be a maximum of 2MB!', $this->event->errors('avatar_name'));
    }
}
