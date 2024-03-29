<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// product routes
$router->get('products','ProductsController@index');
$router->get('products/{id}','ProductsController@show');
$router->put('products/{id}','ProductsController@update');
$router->post('products','ProductsController@store');
$router->delete('products/{product}','ProductsController@destroy');