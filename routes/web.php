<?php

$router->get('/key', function () {
    return str_random(32);
});

$router->get('/', function () use ($router) {
    return 'Home Page!';
});

$router->group(['prefix' => '/api'], function() use ($router) {
    $router->get('/authors', 'AuthorsController@index'); // GET /api/authors (all authors)
    $router->get('/authors/{author}', 'AuthorsController@show'); // Get one author - GET /api/authors/23
    $router->post('/authors', 'AuthorsController@store'); // Create an author - POST /api/authors
    
});

/* 
Edit an author - PUT /api/authors/23
Delete an author - DELETE /api/authors/23
*/