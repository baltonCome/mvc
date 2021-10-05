<?php

namespace App\Http\Middleware;

use \App\Model\Entity\User;

class UserBasicAuth{


    private function getBasicAuthUser(){

        if(!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])){
            return false;
        }

        $user = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);
        if(!$user instanceof User){
            return false;
        }

        return \password_verify($_SERVER['PHP_AUTH_PW'], $user->password) ? $user : false;
        return true;
    }

    private function basicAuth($request){
        
        if($user = $this->getBasicAuthUser()){

            $request->user = $user; 
            return true;
        }
        throw new \Exception("Invalid Authentication data", 403);      
    }

    public function handle($request, $next){

        $this->basicAuth($request);
        return $next($request);
    }
}