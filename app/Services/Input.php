<?php

namespace App\Services;

class Input
{
    // Simpan input ke dalam sesi
    public static function withInput(array $data)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['old_input'] = $data;
    }

    // Ambil input lama berdasarkan kunci
    public static function old($key, $default = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = $_SESSION['old_input'][$key] ?? $default;
        return $data;
    }

    // Hapus old input setelah diambil (opsional)
    public static function clearOldInput()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['old_input']);
    }
}