<?php

namespace App\Middleware;

use App\Middleware\Middleware;

use App\Services\Auth;
class AuthMiddleware extends Middleware
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