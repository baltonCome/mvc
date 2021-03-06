<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddleQueue;

class Router{

    private $url = '';
    private $prefix = '';
    private $routes = [];
    private $request;
    private $contentType = 'text/html';

    //Construtor and instance of the class Request
    public function __construct($url){
 
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    public function setContentType($contentType){
        $this->contentType = $contentType;
    }

    //Defining routes prefix
    private function setPrefix(){

        $parseUrl = parse_url($this->url);
        $this->prefix = $parseUrl['path'] ?? '';
    }

    //Adds route to class
    private function addRoute($method, $route, $params = []){
        
        foreach ($params as $key => $value) {
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $params['middlewares'] = $params['middlewares'] ?? [];

        $params['variables'] = [];

        $patternVariable = '/{(.*?)}/';
        if(\preg_match_all($patternVariable, $route, $matches)){
            $route = \preg_replace($patternVariable,'(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        $patternRoute = '/^'.str_replace('/','\/', $route).'$/';
        $this->routes[$patternRoute][$method] = $params;
    }

    public function get($route, $params = []){
        
        return $this->addRoute('GET', $route, $params);
    }

    public function put($route, $params = []){

        return $this->addRoute('PUT', $route, $params);
    }

    public function post($route, $params = []){

        return $this->addRoute('POST', $route, $params);
    }

    public function delete($route, $params = []){

        return $this->addRoute('DELETE', $route, $params);
    }

    //returns the URI without the prefix
    private function getUri(){
 
        $uri = $this->request->getUri();
    
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        return rtrim(end($xUri));
    }

    //gets the data of the actual route
    private function getRoute(){

        $uri = $this->getUri();
        $httpMethod = $this->request->getHttpMethod();
        
        foreach ($this->routes as $patternRoute=>$methods) {
            if(preg_match($patternRoute, $uri, $matches)){
                if(isset($methods[$httpMethod])){

                    unset($matches[0]);
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this ->request;
                    return $methods[$httpMethod];
                }
                throw new Exception("Error Processing Request, method not allowed", 405);
            }
        }
        throw new Exception("Error Processing Request, Page not found", 404);
    }

    //Runs the actual route
    public function run(){

        try {
            $route = $this->getRoute();
            if(!isset($route['controller'])){
                throw new Exception("Error Processing Request, could not load URL", 500);  
            }
            $args = [];
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            return (new MiddleQueue($route['middlewares'], $route['controller'], $args))->next($this->request);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage(), $this->contentType); 
        }
    }

    private function getErrorMessage($message){

        switch ($this->contentType) {
            case 'application/json':
                return[
                    'error' => $message
                ];
                break;
            
            default:
                return $message;
                break;
        }
    }

    public function getCurrentUrl(){

        return $this->url.$this->getUri();
    }

    public function redirect($route){

        $url = $this->url.$route;
        header('location: '.$url);
        exit;
    }
}