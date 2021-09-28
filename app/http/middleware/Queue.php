<?php

namespace App\Http\Middleware;

class Queue{

    private static $map = [];
    private static $default = [];
    private $middlewares = [];
    private $controller;
    private $conttrollerArgs = [];

    public function __construct($middlewares, $controller, $conttrollerArgs){

        $this->middlewares = \array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->conttrollerArgs = $conttrollerArgs;
    }

    public static function setMap($map){
        
        self::$map = $map;
    }

    public static function setDefault($default){
        
        self::$default = $default;
    }

    public function next($request){

        if(empty($this->middlewares)) return \call_user_func_array($this->controller, $this->conttrollerArgs);

        $middlewares = \array_shift($this->middlewares);

        if(!isset(self::$map[$middlewares])){

            throw new Exception("Error processing middleware from request", 500);
        }

        $queue = $this;
        $next = function($request) use($queue){
            return $queue->next($request);
        };

        return(new self::$map[$middlewares])->handle($request, $next);
    }
}