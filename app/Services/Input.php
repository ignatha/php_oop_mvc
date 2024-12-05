<?php
namespace App\Services;

class Input {
    public static function withInput(array $data)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['old_input'] = $data;
    }

    public static function old($key, $default = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = $_SESSION['old_input'][$key] ?? $default;

        return $data;
    }

    public static function clearOldInput()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['old_input']);
    }
}