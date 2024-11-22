<?php

namespace App\Services;

class Auth {

    private static $key = "yYXG9Myrxeyt8tl4iWdVMD8FPZdjvpP+UBkqdJbta3Z4tsRSHHfd5IofiDVBdZ0Gi+np66VEKp8hni0Kz04/SQ==";

    public static function bcrypt($password)
    {
        return password_hash($password.self::$key,PASSWORD_BCRYPT);
    }

    public static function verifyPassword($password,$dbPasword)
    {
        return password_verify($password.self::$key,$dbPasword);
    }

    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login($user)
    {
        self::startSession();
        $_SESSION['user'] = $user;
    }

    public static function check()
    {
        self::startSession();
        return isset($_SESSION['user']);
    }

    public static function user()
    {
        self::startSession();
        return $_SESSION['user'] ?? null;
    }

    public static function logout()
    {
        self::startSession();
        session_unset();
        session_destroy();
    }


}