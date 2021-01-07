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

// lumen-jwt-auth
$router->group(['prefix' => 'auth'], function ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');
});

// firebase/php-jwt   PHP package for JWT
$router->group(['prefix' => 'php-jwt', 'namespace' => 'PhpJwt'], function ($router) {

    $router->post('login', ['uses' => 'UcAuthController@login']);

    $router->group(['middleware' => 'front.auth'], function ($router) {
        $router->post('demo', ['uses' => 'UcAuthController@demo']);
    });
});