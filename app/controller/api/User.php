<?php

namespace App\Controller\Api;

use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Api{


    public static function getUserItems($request, &$pagination){

        $items = [];

        $quant = EntityUser::getUsers(null,null,null,'Count(*) as quantity')->fetchObject() -> quantity;
        
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        $pagination = new Pagination($quant, $actualPage, 4);

        $results = EntityUser::getUsers(null, 'id DESC', $pagination->getLimit());

        while($user =  $results->fetchObject(EntityUser::class)){

            $items[] = [
                'id' => (int)$user ->id,
                'name' => $user->name,
                'email' => $user->email
            ];
        }
        return $items; 
    }

    //this method returns all the users
    public static function getUsers($request){

        return [
            'users' => self::getUserItems($request, $pagination),
            'pages' => parent::getPagination($request, $pagination)
        ];
    }

    //this one returns only one selected user
    public static function getSelectedUser($request, $id){

        if(!\is_numeric($id)){
            throw new \Exception("Invalid ID ".$id, 400);   
        }

        $user = EntityUser::getUserById($id);
        if(!$user instanceof EntityUser){
            throw new \Exception("Unable to find requested user ".$id, 404);
        }
        return[
            'id' => (int)$user ->id,
            'name' => $user->name,
            'email' => $user->email
        ];
    }

    public static function getCurrentUser($request){

        $user = $request->user;

        return[
            'id' => (int)$user ->id,
            'name' => $user->name,
            'email' => $user->email
        ];
    }

    public static function setNewUser($request){

        $postVars = $request->getPostVars();

        if(!isset($postVars['name']) or !isset($postVars['email']) or !isset($postVars['password'])){
            throw new \Exception("Name, message and password fields are required", 400);
        }

        $userEmail = EntityUser::getUserByEmail($postVars['email']);

        if($userEmail instanceof EntityUser){
            throw new \Exception("Email already exists", 400);
        }

        $user = new EntityUser;
        $user ->name  = $postVars['name'];
        $user ->email  = $postVars['email'];
        $user ->password  = password_hash($postVars['name'], PASSWORD_DEFAULT);
        $user ->save();

        return[
            'id' => (int)$user ->id,
            'name' => $user->name,
            'email' => $user->email
        ];
    }

    public static function setEditUser($request, $id){

        $postVars = $request->getPostVars();

        if(!isset($postVars['name']) or !isset($postVars['email']) or !isset($postVars['password'])){
            throw new \Exception("Name and message fields are required", 400);
        }

        $user = EntityUser::getUserById($id);

        if(!$user instanceof EntityUser){
            throw new \Exception("Unable to find requested user ".$id, 404);
        }

        $userEmail = EntityUser::getUserByEmail($postVars['email']);

        if($userEmail instanceof EntityUser && $userEmail->id != $user->id){
            throw new \Exception("Email already exists", 400);
        }

        $user ->name  = $postVars['name'];
        $user ->email  = $postVars['email'];
        $user ->edit();

        return[
            'id' => (int)$id,
            'name' => $user->name,
            'email' => $user->email
        ];
    }

    public static function setDeleteUser($request, $id){

        $user = EntityUser::getUserById($id);

        if(!$user instanceof EntityUser){
            throw new \Exception("Unable to find requested user ".$id, 404);
        }

        if($user->id == $request->user->id){
            throw new \Exception("Unable to remove connected user ".$id, 404);
        }

        $user ->remove();

        return[
            'success' => true
        ];
    }
} 