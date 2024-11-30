<?php
namespace App\Services;

class Flash
{
    public static function set($key, $message)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash'][$key] = $message;
    }

    public static function get($key)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]); // Hapus setelah diambil
            return $message;
        }

        return null;
    }
}