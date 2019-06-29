<?php

$router->get('/test', function () {
    // TODO: Use this method for testing purposes.

});

$router->get('/key', function () {
    return str_random(32);
});

$router->get('/', function () use ($router) {
    return view('index');
});

$router->group(['prefix' => '/api'], function () use ($router) {
    $router->get('/authors', 'AuthorsController@index'); // GET /api/authors (all authors)
    $router->post('/authors', 'AuthorsController@store'); // Create an author - POST /api/authors
    $router->get('/authors/{author}', 'AuthorsController@show'); // Get one author - GET /api/authors/23
    $router->put('/authors/{author}', 'AuthorsController@update'); // Updates an author - PUT /api/authors/23
    $router->delete('authors/{author}', 'AuthorsController@destroy'); // Deletes an author - DELETE  /api/authors/23
});

// Authentication Routes:
$router->post('/auth/login', 'AuthController@login'); // Login users using Json Web Token tecnique.
