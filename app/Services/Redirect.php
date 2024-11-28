<?php
namespace App\Services;

use App\Services\Input;

class Redirect
{
    public static function back(array $input)
    {
        if (!empty($input)) {
            Input::withInput($input);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; 
    }
    
}