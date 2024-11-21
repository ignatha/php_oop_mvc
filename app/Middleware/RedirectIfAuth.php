<?php

namespace App\Middleware;

use App\Middleware\MiddlewareInterface;

class RedirectIfAuth implements MiddlewareInterface
{
    public function handle($request,$next)
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $next();
    }
}