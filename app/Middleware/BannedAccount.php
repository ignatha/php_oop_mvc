<?php

namespace App\Middleware;

use App\Middleware\MiddlewareInterface;

class BannedAccount implements MiddlewareInterface
{
    public function handle($request,$next)
    {
        if (true) {
            echo "User ini kena banned";
            // exit;
        }

        $next();
    }
}