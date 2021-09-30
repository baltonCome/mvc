<?php

namespace App\Controller\Admin;
use \App\Util\View;

class Page{


    public static function getPage($title, $content){

        return View::render('admin/page', [
            'title' => $title,
            'content' => $content
        ]);
    }
}