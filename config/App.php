<?php

namespace Config;

class App
{
    public static array $middlewareAliases = [
        'auth' => \App\Middleware\Authenticate::class,
        'admin' => \App\Middleware\AdminAuthenticate::class,
        'event' => \App\Middleware\EventAuthenticate::class,
        'event_owner' => \App\Middleware\OwnerAuthenticate::class,
    ];
}
