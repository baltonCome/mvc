<?php

use \App\Http\Response;
use \App\Controller\Admin;

$router->get('/admin',[

    'middlewares' => [
        'required-admin-login'
    ],
    function(){
        return new Response(200, 'afsasf');
    }
]);

$router->get('/admin/login',[

    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

$router->post('/admin/login',[

    'middlewares' => [
        'required-admin-logout'
    ],
    function($request){ 
        return new Response(200, Admin\Login::setLogin($request));
    }
]);

$router->get('/admin/logout',[

    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Login::setLogout($request));
    }
]);