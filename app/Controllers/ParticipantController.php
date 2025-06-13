<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use App\Models\Participant;
use Lib\FlashMessage;

class ParticipantController extends Controller
{
    public function subscribe(Request $request): void
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
}
