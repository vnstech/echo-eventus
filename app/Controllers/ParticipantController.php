<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use App\Models\Participant;
use App\Models\Event;
use Lib\FlashMessage;

class ParticipantController extends Controller
{
    public function index(Request $request): void
    {
        $eventId = $request->getParam('event_id');
        $title = 'Participants';

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        $perPage = 12;

        $paginator = Participant::paginate(
            page: $page,
            per_page: $perPage,
            from: 'participants',
            attributes: ['id', 'name', 'email'],
            conditions: ['event_id' => $eventId],
            route: 'participants.index'
        );

        $event = Event::findById($eventId);
        $participants = $paginator->registers();
        $this->render('user/events/participants/index', compact('title', 'participants', 'paginator', 'event'));
    }

    public function register(Request $request): void
    {
        $params = $request->getParams();
        $params['participant']['event_id'] = $params['event_id'];
        $participant = new Participant($params['participant']);

        if ($participant->save()) {
            FlashMessage::success('Event registration successful! See you there!');
        } else {
            FlashMessage::danger('Something went wrong with your registration. Please try again.');
        }

        $this->redirectTo(route('public.show', ['event_id' => $params['event_id']]));
    }

    public function remove(Request $request): void
    {
        $params = $request->getParams();
        $participant = Participant::findById($params['participant_id']);
        if ($participant && $participant->event_id == $params['event_id']) {
            $participant->destroy();
            FlashMessage::success('Participant removed successfully!');
        } else {
            FlashMessage::danger('Could not remove participant.');
        }
        $this->redirectTo(route('participants.index', ['event_id' => $params['event_id']]));
    }
}
