<?php

namespace App\Controller\Pages;

use App\Util\View;
use App\Model\Entity\Organization;

class Home extends Page{

    public static function getHome(){

        $org = new Organization();

        $content = View::render('pages/home',[
            'name' => $org -> name,
            'description' => $org -> description,
            'site' => $org -> site
        ]);

        return parent::getPage('savagery', $content);
    }
}