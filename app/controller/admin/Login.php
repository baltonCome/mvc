<?php

namespace App\Controller\Admin;

use \App\Util\View;
use \App\Model\Entity\User;
use \App\Session\Admin\Login as SessAdmLog;

class Login extends Page{

    public static function getLogin($request, $errorMessage = null){

        $status = !\is_null($errorMessage) ? View::render('admin/login/status', [
            'message' => $errorMessage
        ]) : '';

        $content = View::render('admin/login', [
            'status' => $status
        ]);

        return parent::getPage('Login', $content);
    }

    public static function setLogin($request){

        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        $user = User::getUserByEmail($email);

        if(!$user instanceof User){
            return self::getLogin($request, 'Acess not allowed, try again...');
        }

        if(!\password_verify($password, $user->password)){
            return self::getLogin($request, 'Acess not allowed, try again...');
        }

        SessAdmLog::login($user);
        $request->getRouter()->redirect('/admin');
    }

    public static function setLogout($request){

        SessAdmLog::logout();
        $request->getRouter()->redirect('/admin/login');
    }
}