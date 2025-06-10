<?php

namespace App\Controllers;
use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\Event;
use App\Models\UserEvent;

class MembersController extends Controller
{
    public function index(Request $request): void
    {
        $eventId = $request->getParam('event_id');
        
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
            conditions: ['users_events.event_id' => $eventId],
            route: 'members.index'
        );

        $event = Event::findById($eventId);

        $ownerId = $event->owner_id; 

        $is_owner = $this->current_user->id === $ownerId;
        $i = 0;
        $members = $paginator->registers();
        foreach ($members as $key => $member) {
            if ($member->id === $event->owner_id) {
                unset($members[$key]);
                array_unshift($members, $member);
                $i++;
                break;
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

    public function add(Request $request): void
    {
        $params = $request->getParams();

        $user = User::findBy(['email' => $params['email']]);
        if (!$user) {
            FlashMessage::danger('User with this email does not exist.');
            $this->redirectTo(route('members.new', ['event_id' => $params['event_id']]));
        } else {

            $checkPivot = UserEvent::where([
                'user_id' => $user->id,
                'event_id' => $params['event_id'],
            ]);

            if (empty($checkPivot)) {

                $pivot = new UserEvent([
                    'user_id' => $user->id,
                    'event_id' => $params['event_id'],
                ]);

                if ($pivot->save()) {
                    FlashMessage::success('Member added successfully!');
                    $this->redirectTo(route('members.index', ['event_id' => $params['event_id']]));
                } else {
                    FlashMessage::danger('Could not add member. Please check the data.');
                    $this->redirectTo(route('members.new', ['event_id' => $params['event_id']]));
                }
            } else {
                FlashMessage::danger('This member is already added to the event.');
                $this->redirectTo(route('members.new', ['event_id' => $params['event_id']]));
            }
        }
    }


    public function remove(Request $request) {
        $params = $request->getParams();
        $event = Event::findById($params['event_id']);
        if($event->owner_id != $params['user_id']) {
            $pivot = UserEvent::findBy([
                'user_id' => $params['user_id'],
                'event_id' => $params['event_id'],
            ]);
            
            if ($pivot->destroy()) {
                FlashMessage::success('Member removed successfully!');
            } else {
                FlashMessage::danger('Could not remove member. Please check the data.');
            }
            $this->redirectTo(route('members.index', ['event_id' => $params['event_id']]));
        } else {
            FlashMessage::danger('You cannot remove the event owner from the event.');
            $this->redirectTo(route('members.index', ['event_id' => $params['event_id']]));
        }
    } 
}