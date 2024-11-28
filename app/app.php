<?php
namespace App;

use App\Services\Route;
use App\Middleware\{AuthMiddleware, RedirectIfAuth,ClearOldInputMiddleware};

$route = new Route;

// Global Middleware
$route->middleware([
    ClearOldInputMiddleware::class
]);

$route->get('/','HomeController@index',[AuthMiddleware::class]);
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


