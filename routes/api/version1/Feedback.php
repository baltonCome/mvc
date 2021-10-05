<?php

use \App\Http\Response;
use \App\Controller\Api;


$router->get('/api/version1/feedback', [
    'middlewares' => [
        'api'
    ],
    function($request){
        return new Response(200, Api\Feedback::getFeedback($request), 'application/json');
    }
]);

$router->get('/api/version1/feedback/{id}', [
    'middlewares' => ['api'],
    function($request, $id){
        return new Response(200, Api\Feedback::getSelectedFeedback($request, $id), 'application/json');
    }
]);

$router->post('/api/version1/feedback', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id){
        return new Response(201, Api\Feedback::setNewFeedback($request), 'application/json');
    }
]);

$router->put('/api/version1/feedback/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id){
        return new Response(200, Api\Feedback::setEditFeedback($request, $id), 'application/json');
    }
]);

$router->delete('/api/version1/feedback/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id){
        return new Response(200, Api\Feedback::setDeleteFeedback($request, $id), 'application/json');
    }
]);