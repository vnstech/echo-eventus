<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\FlashMessage;
use Lib\Authentication\AuthEventOwner;

class OwnerAuthenticate implements Middleware
{
    public function handle(Request $request): void
    {
        $eventId = $request->getParam('event_id');

        if (!AuthEventOwner::check($eventId)) {
            FlashMessage::danger('You are not authorized');
            $this->redirectTo(route('events.index'));
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
