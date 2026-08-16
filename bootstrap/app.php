<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn (\Illuminate\Http\Request $request) => match (true) {
            $request->is('admin/*') || $request->is('admin') => route('login.admin'),
            $request->is('teacher/*') || $request->is('teacher') => route('login.teacher'),
            $request->is('student/*') || $request->is('student') => route('login.student'),
            default => '/',
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
