<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class EventsController extends Controller
{
    public function index(): void
    {
        $title = 'Events';
        $this->render('user/events/index', compact('title'));
    }

    public function new(Request $request): void
    {
        $title = 'Events new';
        $this->render('user/events/new', compact('title'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        error_log(print_r($params, true));
        $event = $this->current_user->event()->new($params['event']);

        if ($event->save()) {
            FlashMessage::success('Event created successfully!');
            $this->redirectTo(route('events.index'));
        } else {
            FlashMessage::danger('There is incorrect data! Please check!');
            $title = 'Events new';
            $this->render('user/events/new', compact('title'));
        }
    }
}
