<?php

namespace App\Http\Middleware;

use \App\Model\Entity\User;
use \Firebase\JWT\JWT;

class JWTAuth{


    private function getJWTAuthUser($request){

        $headers = $request->getHeaders();

        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

        try {
            $decode = (array)JWT::decode($jwt, \getenv('JWT_KEY'),['HS256']);
        } catch (\Exception $e) {
            throw new \Exception("Invalid token", 403);
        }
        
        $email = $decode['email'] ?? '';

        $user = User::getUserByEmail($email);

        return $user instanceof User ? $user : false;
    }

    private function auth($request){
        
        if($user = $this->getJWTAuthUser($request)){

            $request->user = $user; 
            return true;
        }
        throw new \Exception("Invalid Token", 403);      
    }

    public function handle($request, $next){

        $this->auth($request);
        return $next($request);
    }
}