<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{

    public $id;
    public $name;
    public $email;
    public $password;

    public function save(){

       
    }

    public static function getUserByEmail($email){

        return(new Database('users'))-> select('email ="'.$email.'"')->fetchObject(self::class);
    }
}