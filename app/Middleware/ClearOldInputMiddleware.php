<?php

namespace App\Middleware;

use App\Middleware\MiddlewareInterface;
use App\Services\Input;

class ClearOldInputMiddleware implements MiddlewareInterface
{
    public function handle($request,$next)
    {
        $next();
    }

    public function terminate($request,$response)
    {
        Input::clearOldInput();
        $response();       
    }
}