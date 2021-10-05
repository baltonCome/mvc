<?php

namespace App\Controller\Admin;

use App\Util\View;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;


class User extends Page{

    public static function getUserItems($request, &$pagination){

        $items = '';

        $quant = EntityUser::getUsers(null,null,null,'Count(8) as quantity')->fetchObject() -> quantity;
        
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        $pagination = new Pagination($quant, $actualPage, 4);

        $results = EntityUser::getUsers(null, 'id DESC', $pagination->getLimit());

        while($user =  $results->fetchObject(EntityUser::class)){

            $items .= View::render('admin/modules/users/item',[
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]);
        }
        return $items; 
    }

    public static function getStatus($request){

        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            case 'created':
                return Alert::getSuccess('user saved');
                break;
            case 'updated': 
                return Alert::getSuccess('user Updated');
                break;
            case 'deleted': 
                return Alert::getSuccess('user deleted');
                break;
            case 'duplicated': 
                return Alert::getSuccess('Email inserted already exists');
                break;
        }
    }

    public static function getUsers($request){

        $content = View::render('admin/modules/users/index',[
            'items' => self::getUserItems($request, $pagination),
            'pagination' => parent::getPagination($request, $pagination),
            'status' => self::getStatus($request)
        ]);

        return parent::getPanel('Users', $content, 'users');
    }

    public static function getNewUser($request){

        $content = View::render('admin/modules/users/form',[
            'title' => 'Add user',
            'name' => '',
            'email' => '',
            'status' => self::getStatus($request)
        ]);

        return parent::getPanel('New user', $content, 'users');
    }

    public static function setNewUser($request){

        $postVars = $request ->getPostVars();

        $name = $postVars['name'] ?? '';
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        $userEmail = EntityUser::getUserByEmail($email);
        if($userEmail instanceof EntityUser){

            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }

        $user = new EntityUser();

        $user->name = $name;
        $user->email = $email;
        $user->password = \password_hash($password, PASSWORD_DEFAULT);
        $user->save();

        $request->getRouter()->redirect('/admin/users/'.$user->id.'/edit?status=created');
    }

    public static function getEditUser($request, $id){

        $user = EntityUser::getUserById($id);

        if(!$user instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        $content = View::render('admin/modules/users/form',[
            'title' => 'Edit  Message',
            'name' => $user->name,
            'email' => $user->email,
            'status' => self::getStatus($request)
        ]);

        return parent::getPanel('Edit', $content, 'users');
    }

    public static function setEditUser($request, $id){

        $user = EntityUser::getUserById($id);

        if(!$user instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        $postVars = $request->getPostVars();
        $name = $postVars['name'] ?? '';
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        $userEmail = EntityUser::getUserByEmail($email);
        if($userEmail instanceof EmtityUser && $userEmail->id != $id){

            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }

        $user ->name = $name;
        $user ->email = $email;
        $user ->password = \password_hash($password, PASSWORD_DEFAULT);
        $user -> edit();

        $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=updated');
    }

    public static function getDeleteUser($request, $id){

        $user = EntityUser::getUserById($id);

        if(!$user instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        $content = View::render('admin/modules/users/delete',[
            'name' => $user->name,
            'email' => $user->email
        ]);

        return parent::getPanel('Delete', $content, 'users');
    }

    public static function setDeleteUser($request, $id){

        $user = EntityUser::getUserById($id);

        if(!$user instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }

        $user->remove();

        $request->getRouter()->redirect('/admin/users?status=deleted');
    }
}