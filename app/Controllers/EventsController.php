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

    public function new(): void
    {
        $title = 'Events new';
        $this->render('user/events/new', compact('title'));
    }
}
