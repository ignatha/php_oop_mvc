<?php

namespace App\Middleware;

use App\Middleware\MiddlewareInterface;

use App\Services\Auth;
class RedirectIfAuth implements MiddlewareInterface
{
    public function handle($request,$next)
    {
        if (Auth::check()) {
            header('Location: /');
            exit;
        }

        $next();
    }

    public function terminate($request,$response)
    {
        $response();   
    }
}