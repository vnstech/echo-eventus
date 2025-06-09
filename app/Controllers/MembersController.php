<?php

namespace App\Controllers;
use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\Event;

class MembersController extends Controller
{
    public function index(Request $request): void
    {
        $params = $request->getParams();
        
        $title = 'Members';

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        $perPage = 12;

        $paginator = User::paginate(
            page: $page,
            per_page: $perPage,
            from: 'users INNER JOIN users_events ON users.id = users_events.user_id',
            attributes: ['users.id', 'users.name', 'users.email'],
            conditions: ['users_events.event_id' => $params['event_id']],
            route: 'members.index'
        );
        $event = Event::findById($params['event_id']);
        $ownerId = $event->owner_id; 
        $is_owner = $this->current_user->id === $ownerId;
        $event = $this->current_user->usersEvents()->findById($params['event_id']);


        $members = $paginator->registers();

        foreach ($members as $key => $member) {
            if ($member->id === $ownerId) {
                unset($members[$key]);
            }
        }

        $this->render('user/events/members/index', compact('title', 'members', 'paginator', 'is_owner', 'event'));
    }

    public function new(Request $request): void
    {
        $params = $request->getParams();
        $title = 'Members new';
        $event = Event::findById($params['event_id']);
        $this->render('user/events/members/new', compact('title', 'event'));
    }
}