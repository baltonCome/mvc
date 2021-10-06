<?php

namespace App\Controller\Api;

use \App\Model\Entity\User;
use \Firebase\JWT\JWT;

class Auth extends Api{

    public static function generateToken($request){

        $postVars = $request->getPostVars();

        if(!isset($postVars['email']) or !isset($postVars['password'])){
            throw new \Exception("Email and Password fields are required", 400);
        }

        $user =  User::getUserByEmail($postVars['email']);
        if(!$user instanceof User){
            throw new \Exception("Invalid data", 400);
            
        }

        if(!\password_verify($postVars['password'], $user->password)){
            throw new \Exception("Invalid data", 400);
            
        }

        $payload = [
            'email' => $user->email
        ];

        return [
            'token' => JWT::encode($payload, \getenv('JWT_KEY'))
        ];
    }
}