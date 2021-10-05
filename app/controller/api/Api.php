<?php

namespace App\Controller\Api;

class Api{


    public static function getDetails($request){

        return [
            'nome' => 'Savagery - API',
            'version' => '1.0',
            'author' => 'Balton Come',
            'email' => 'baltoncome@outlook.com'
        ];
    }

    protected static function getPagination($request, $pagination){

        $queryParams = $request->getQueryParams();
        $pages = $pagination->getPages();

        return[
            'actualPage' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'pagesQuantity' => !empty($pages) ? count($pages) : 1
        ];
    }
}