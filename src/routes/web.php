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
    return 'Base API';
});

// Tasks
// TODO: add middleware -> auth
$router->group(['prefix' => 'api'], function () use ($router) {

    // Login(JWT)
    $router->post('login', 'AuthController@login');

    // Registering Users
    $router->post('register/manager', 'AuthController@registerManager');

    $router->post('register/technician', 'AuthController@registerTechnician');


    // Tasks
    $router->group(['prefix' => 'tasks'], function() use ($router) {

        // Lists all tasks available to the user
        $router->get('/', ['uses' => 'TasksController@listAll', 'middleware' => 'auth']);
    
        $router->get('{taskId}', ['uses' => 'TasksController@listSingle', 'middleware' => 'auth']);

        $router->get('user/{userId}', ['uses' => 'TasksController@listAllByUserId', 'middleware' => 'auth']);

        $router->post('create', ['uses' => 'TasksController@create', 'middleware' => 'auth']);
    
        $router->put('{taskId}', ['uses' => 'TasksController@update', 'middleware' => 'auth']);
    
        $router->delete('{taskId}', ['uses' => 'TasksController@delete', 'middleware' => 'auth']);

    });


    // Users
    $router->group(['prefix' => 'users'], function()  use ($router) {

        // Lists all users of a type
        $router->get('technicians', ['uses' => 'UsersController@listTechnicians', 'middleware' => 'auth']);

        $router->get('{userId}', ['uses' => 'UsersController@listSingle', 'middleware' => 'auth']);
    });

});



// Notifications


