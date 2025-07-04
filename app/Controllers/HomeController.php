<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;
use Core\Http\Request;
use App\Models\Event;

class HomeController extends Controller
{
    public function index(): void
    {
        if (Auth::check()) {
            $this->redirectTo(route('events.index'));
        }
        $title = 'Home';

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        $perPage = 12;

        $paginator = Event::paginate(
            page: $page,
            per_page: $perPage,
            from: 'events',
            attributes: [
                'name',
                'start_date',
                'finish_date',
                'owner_id',
                'status',
                'description',
                'location_name',
                'address',
                'category',
                'two_fa_check_attendance',
                'avatar_name',
            ],
            route: 'public.index'
        );

        $events = $paginator->registers();

        $this->render('visitor/home/index', compact('title', 'events', 'paginator'));
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $event = Event::findById($params['event_id']);

        if ($event) {
            $title = "Event Painel";
            $this->render('visitor/home/show', compact('title', 'event'));
        } else {
            $title = "Event Error";
            $this->render('visitor/home/error', compact('title'));
        }
    }
}
