<?php

use \App\Http\Response;
use \App\Controller\Api;


$router->get('/api/version1/users', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(200, Api\User::getUsers($request), 'application/json');
    }
]);

$router->post('/api/version1/users/me', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(201,Api\User::getCurrentUser($request), 'application/json');
    }
]);

$router->get('/api/version1/users/me', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(201,Api\User::getCurrentUser($request), 'application/json');
    }
]);

$router->get('/api/version1/users/{id}', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request, $id){
        return new Response(200, Api\User::getSelectedUser($request, $id), 'application/json');
    }
]);

$router->post('/api/version1/users', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request, $id){
        return new Response(201, Api\User::setNewUser($request), 'application/json');
    }
]);

$router->put('/api/version1/users/{id}', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request, $id){
        return new Response(200, Api\User::setEditUser($request, $id), 'application/json');
    }
]);

$router->delete('/api/version1/users/{id}', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request, $id){
        return new Response(200, Api\User::setDeleteUser($request, $id), 'application/json');
    }
]);