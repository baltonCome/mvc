<?php

namespace App\Controller\Admin;

use App\Util\View;


class Home extends Page{

    public static function getHome($request){

        $content = View::render('admin/modules/home/index',[]);

        return parent::getPanel('Home', $content, 'home');
    }
}