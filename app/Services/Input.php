<?php
namespace App\Services;

class Input {
    public static function withInput(array $data)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION['old_input'] = $data;
    }

    public static function old($key, $default = null)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $data = $_SESSION['old_input'][$key] ?? $default;

        return $data;
    }

    public static function clearOldInput()
    {
        if (isset($_SESSION)) {
            unset($_SESSION['old_input']);
        }
    }
}