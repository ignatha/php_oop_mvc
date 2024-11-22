<?php

namespace App\Middleware;

use App\Middleware\MiddlewareInterface;

use App\Services\Auth;
class AuthMiddleware implements MiddlewareInterface
{
    public function handle($request,$next)
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $next();
    }
}