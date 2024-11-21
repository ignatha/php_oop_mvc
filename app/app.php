<?php
namespace App;

use App\Services\Route;
use App\Middleware\{BannedAccount, RedirectIfAuth};

$route = new Route;

$route->get('/','HomeController@index',[RedirectIfAuth::class]);
$route->get('/login','HomeController@login');

$route->get('/user/add','HomeController@add');
$route->post('/user/add','HomeController@store');

$route->get('/user/edit/{id}','HomeController@edit');
$route->post('/user/update/{id}','HomeController@update');

$route->post('/user/delete/{id}','HomeController@delete');

$route->get('/home','HomeController@home');

$route->run();


