<?php

use App\Http\Controllers\AuthorsController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/key', function () {
    return str_random(32);
});

$router->get('/', function () use ($router) {
    return 'Home Page!';
});

$router->group(['prefix' => '/api'], function() use ($router) {
    $router->get('/authors', 'AuthorsController@index'); // GET /api/authors (all authors)
    
});

/* 
Get one author - GET /api/authors/23
Create an author - POST /api/authors 
Edit an author - PUT /api/authors/23
Delete an author - DELETE /api/authors/23
*/