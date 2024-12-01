<?php

namespace App\Middleware;

use App\Middleware\Middleware;

class BannedAccount extends Middleware
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