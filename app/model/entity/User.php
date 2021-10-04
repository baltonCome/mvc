<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{

    public $id;
    public $name;
    public $email;
    public $password;

    public function save(){

       $this->id = (new Database ('users'))->insert([
           'name' => $this->name,
           'email' => $this->email,
           'password' => $this->password
       ]);
       return true;
    }

    public function edit(){

        $this->id = (new Database ('users'))->update('id = '.$this->id,[
           'name' => $this->name,
           'email' => $this->email,
           'password' => $this->password
       ]);
       return true;
    }

    public static function getUserByEmail($email){

        return(new Database('users'))-> select('email ="'.$email.'"')->fetchObject(self::class);
    }

    public static function getUsers($where =  null, $order = null, $limit = null, $fields = '*'){

        return(new Database('users'))-> select($where, $order, $limit, $fields);
    }

    public static function getUserById($id){

        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }

    public function remove(){

        return (new Database ('users'))->delete('id = '.$this->id);
    }
} 