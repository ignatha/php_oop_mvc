<?php

namespace App\Services;

use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Aws\Exception\AwsException;

class CloudStorage implements FileStorageInterface
{
    protected static $s3Client;
    protected static $bucket;

    public function __construct()
    {
        $config = [
            'region'  => 'auto',
            'bucket'  => 'web',
            'account_id' => "",
            'access_key_id' => "",
            'access_key_secret' => "",
            'endpoint' => "",
        ];

        $credentials = new Credentials($config['access_key_id'], $config['access_key_secret']);

        self::$s3Client = new S3Client([
            'version' => 'latest',
            'region'  => $config['region'],
            'endpoint' => $config['endpoint'],
            'credentials' => $credentials
        ]);

        self::$bucket = $config['bucket'];
    }

    public static function upload($file, $path)
    {
        $instance = new self();
        try {
            $result = self::$s3Client->putObject([
                'Bucket' => self::$bucket,
                'Key'    => $path,
                'SourceFile' => $file['tmp_name'],
                'ACL'    => 'public-read', // Public access
            ]);
            return $path;
        } catch (\Exception $e) {
            throw new \Exception("Failed to upload file: " . $e->getMessage());
        }
    }

    public static function delete($path)
    {
        $instance = new self();
        self::$s3Client->deleteObject([
            'Bucket' => self::$bucket,
            'Key'    => $path,
        ]);
    }

    public static function getUrl($objectKey)
    {
        $instance = new self();

        try {
            $cmd = self::$s3Client->getCommand('GetObject', [
                'Bucket' => self::$bucket,
                'Key'    => $objectKey,
            ]);
            $request = self::$s3Client->createPresignedRequest($cmd, '+1 hour');
        
            return (string) $request->getUri();
        } catch (AwsException $e) {
            throw new \Exception("Failed to upload file: " . $e->getMessage());
        }
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
