<?php

namespace App\Middleware;

use App\Middleware\Middleware;
use App\Services\Input;

class ClearOldInputMiddleware extends Middleware
{
    public function terminate($request,$response)
    {
        Input::clearOldInput();
        $response();
    }

}