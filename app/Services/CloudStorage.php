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
            'account_id' => "721a053148a2d968efcea7bd0cfbcae1",
            'access_key_id' => "fd67378b88474bcc9b75934454f557b3",
            'access_key_secret' => "57ba5add9fa6fe405a7ca8d8a72fbef0142de79941ae528becfb72f1054e24fb",
            'endpoint' => 'https://721a053148a2d968efcea7bd0cfbcae1.r2.cloudflarestorage.com',
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
