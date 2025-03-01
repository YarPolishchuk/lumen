<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/user/register', ['uses' => 'UserController@register']);
    $router->post('/user/sign-in', ['uses' => 'UserController@login']);
    $router->patch('/user/recover-password', ['uses' => 'UserController@recoverPassword']);
    $router->get('user/companies', ['uses' => 'UserController@getUsersWithCompanies']);
    $router->post('user/companies', ['uses' => 'UserController@addCompany']);
});

