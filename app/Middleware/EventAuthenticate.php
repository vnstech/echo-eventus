<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\FlashMessage;
use Lib\Authentication\AuthUserEvents;

class EventAuthenticate implements Middleware
{
    public function handle(Request $request): void
    {
        $eventId = $request->getParam('event_id');

        if (!AuthUserEvents::check($eventId)) {
            FlashMessage::danger('Event not accessible');
            $this->redirectTo(route('events.index'));
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
