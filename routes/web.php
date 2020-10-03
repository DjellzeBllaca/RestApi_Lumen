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

$router->group(['prefix'=>'api'], function() use($router){

    $router->group(['prefix'=>'user'], function() use($router){
        $router->post('register','UserController@register');
        $router->post('login','UserController@login');
        $router->post('post', 'PostsController@store');
        $router->put('update/{id}', 'PostsController@update');
        $router->delete('delete/{id}', 'PostsController@destroy');
        $router->post('comment', 'CommentsController@create');

    });

    $router->group(['prefix'=>'admin'], function() use($router){
        $router->post('register','AdminController@register');
        $router->post('login','AdminController@login');
    });

    Route::post('me', 'UserController@me');
    Route::post('logout', 'UserController@logout');
    Route::post('refresh', 'UserController@refresh');

});
