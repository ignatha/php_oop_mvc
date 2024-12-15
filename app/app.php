<?php
namespace App;

use App\Services\Route;
use App\Middleware\{AuthMiddleware, RedirectIfAuth, ClearOldInputMiddleware};

$route = new Route;

$route->middleware([
    ClearOldInputMiddleware::class
]);  // middleware global yang akan dijalankan di setiap request

$route->get('/assets/{path:*}',function($path){
    
    // mengambil folder nya
    $filePath = __DIR__ . "/../public/{$path}";

    // cek apakah file ada
    if (file_exists($filePath) && is_file($filePath)) {

        // type file mime yang valid di browser header
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
        ];

        // set heder content type berdasar jenis file nya
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeType = $mimeTypes[$ext] ?? mime_content_type($filePath);
        header("Content-Type: $mimeType");

        // menampilkan file
        readfile($filePath);
        exit;
    }

    // jike file tidak ada
    http_response_code(404);
    echo "File not found.";


});

$route->get('/','HomeController@index',[AuthMiddleware::class]); // route middleware dijalankan spesifik tiap route yang di definisikan
$route->get('/login','HomeController@login',[RedirectIfAuth::class]);
$route->post('/login','HomeController@loginStore',[RedirectIfAuth::class]);

$route->get('/user/add','HomeController@add');
$route->post('/user/add','HomeController@store');

$route->get('/user/edit/{id}','HomeController@edit');
$route->post('/user/update/{id}','HomeController@update');

$route->post('/user/delete/{id}','HomeController@delete');

$route->get('/home','HomeController@home');

$route->get('/logout','HomeController@logout');

$route->run();


