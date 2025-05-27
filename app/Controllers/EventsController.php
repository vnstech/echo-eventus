<?php

namespace App\Controllers;

use App\Models\Event;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;
use Lib\Paginator;

class EventsController extends Controller
{
    public function index(): void
    {
        $title = 'Events';

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        $perPage = 12;

        $paginator = new Paginator(
            class: Event::class,
            page: $page,
            per_page: $perPage,
            table: 'events',
            attributes: [
                'name',
                'start_date',
                'finish_date',
                'user_id',
                'status',
                'description',
                'location_name',
                'address',
                'category',
                'two_fa_check_attendance'
            ],
            conditions: ['user_id' => $this->current_user->id],
            route: 'events.index'
        );

        $events = $paginator->registers();

        $this->render('user/events/index', compact('title', 'events', 'paginator'));
    }


    public function new(Request $request): void
    {
        $title = 'Events new';
        $this->current_user->event()->new();
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

    public function show(Request $request): void
    {
        $params = $request->getParams();
        /** @var Event|null $event */
        $event = $this->current_user->event()->findById($params['event_id']);

        if ($event) {
            $title = "Event Painel";
            $this->render('user/events/show', compact('title', 'event'));
        } else {
            $title = "Event Error";
            $this->render('user/events/error', compact('title'));
        }
    }
}
