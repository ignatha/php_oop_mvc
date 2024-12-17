<?php

namespace App\Services;

interface FileStorageInterface
{
    public static function upload($file, $path);
    public static function delete($path);
    public static function getUrl($path);
}
