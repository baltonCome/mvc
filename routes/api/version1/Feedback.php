<?php

use \App\Http\Response;
use \App\Controller\Api;


$router->get('/api/version1/feedback', [
    'middlewares' => ['api'],
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