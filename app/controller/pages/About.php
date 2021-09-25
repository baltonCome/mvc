<?php

namespace App\Controller\Pages;

use App\Util\View;
use App\Model\Entity\Organization;

class About extends Page{

    public static function getAbout(){

        $org = new Organization();

        $content = View::render('pages/about',[
            'name' => $org -> name,
            'description' => $org -> description,
            'site' => $org -> site
        ]);

        return parent::getPage('About', $content);
    }
}