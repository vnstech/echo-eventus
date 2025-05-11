<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class EventsController extends Controller
{
    public function index(): void
    {
        $title = 'Events';
        $this->render('user/events/index', compact('title'));
    }
}
