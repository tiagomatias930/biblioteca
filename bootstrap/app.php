<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // The app has no default "login" route (auth is admin-only,
        // under /admin), so the 'auth' middleware must be told where
        // to send unauthenticated visitors instead of the framework
        // default of route('login').
        $middleware->redirectGuestsTo(fn () => route('admin.login'));

        // Authenticated admins hitting /admin/login are sent to the
        // dashboard instead of the framework default of route('home')
        // — 'home' is the public catalogue here, not an admin area.
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
