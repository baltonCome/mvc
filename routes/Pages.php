<?php

use \App\Http\Response;
use \App\Controller\Pages;

$router->get('',[
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);

$router->get('/about',[
    function(){
        return new Response(200, Pages\About::getAbout());
    }
]);

$router->get('/feedback',[
    function($request){
        return new Response(200, Pages\Feedback::getFeedback($request));
    }
]);

$router->POST('/feedback',[
    function($request){
        return new Response(200, Pages\Feedback::saveFeedback($request));
    }
]);