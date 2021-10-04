<?php

use \App\Http\Response;
use \App\Controller\Admin;

$router->get('/admin/feedback',[

    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Feedback::getFeedback($request));
    }
]);

$router->get('/admin/feedback/new',[

    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Feedback::getNewFeedback($request));
    }
]);

$router->post('/admin/feedback/new',[

    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Feedback::setNewFeedback($request));
    }
]);

$router->get('/admin/feedback/{id}/edit',[

    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Feedback::getEditFeedback($request, $id));
    }
]);

$router->post('/admin/feedback/{id}/edit',[

    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Feedback::setEditFeedback($request, $id));
    }
]);

$router->get('/admin/feedback/{id}/delete',[

    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Feedback::getDeleteFeedback($request, $id));
    }
]);

$router->post('/admin/feedback/{id}/delete',[

    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200, Admin\Feedback::setDeleteFeedback($request, $id));
    }
]);