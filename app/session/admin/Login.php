<?php

namespace App\Session\Admin;

class Login{

    private static function init(){
        if(\session_status() != PHP_SESSION_ACTIVE){
            \session_start();
        }
    }

    public static function login($user){

        self::init();
        $_SESSION['admin']['user'] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];
        return true;
    }

    public static function isLogged(){

        self::init();

        return isset($_SESSION['admin']['user']['id']);
        
    } 

    public static function logout(){

        self::init();
        unset($_SESSION['admin']['user']['id']);

        return true;
    }
}