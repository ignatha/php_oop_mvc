<?php

namespace App\Services;

class LocalStorage implements FileStorageInterface
{
    protected static $basePath;

    public function __construct()
    {
        self::$basePath = rtrim(__DIR__ . '/../../public/', '/') . '/';
    }


    public static function upload($file, $path)
    {
        $instance = new self();
        $destination = self::$basePath . ltrim($path, '/');
        $directory = dirname($destination);

        // Buat folder jika tidak ada
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        // Pindahkan file ke lokasi tujuan
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $path;
        }

        throw new \Exception("Failed to upload file.");
    }

    public static function delete($path)
    {
        $instance = new self();
        $filePath = self::$basePath . ltrim($path, '/');
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public static function getUrl($path)
    {
        $instance = new self();
        return '/assets/'.ltrim($path, '/');
    }

    public static function name($name, $separator = '-',$folder) {
        [$string,$ext] = explode('.',$name);
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $slug = strtolower($slug);

        $slug = preg_replace('/[^a-z0-9\s]/', '', $slug);

        $slug = preg_replace('/\s+/', $separator, $slug);

        $slug = trim($slug, $separator);

        $slug .= $separator.str_replace('.', '', microtime(true) );
    
        return "{$folder}/{$slug}.{$ext}";
    }
    
}
