<?php

namespace App\Controller\Pages;

use \App\Util\View;
use \App\Model\Entity\Feedback as EntityFeedback;
use \WilliamCosta\DatabaseManager\Pagination;

class Feedback extends Page{


    public static function getFeedbackItems($request, &$pagination){

        $items = '';

        $quant = EntityFeedback::getFeedback(null,null,null,'Count(8) as quantity')->fetchObject() -> quantity;
        
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        $pagination = new Pagination($quant, $actualPage, 4);

        $results = EntityFeedback::getFeedback(null, 'id DESC', $pagination->getLimit());

        while($feedback =  $results->fetchObject(EntityFeedback::class)){

            $items .= View::render('pages/feedback/items',[
                'name' => $feedback->name,
                'message' => $feedback->message,
                'date' => date('d/m/Y H:i:s', \strtotime($feedback->date))
            ]);
        }
        return $items; 
    }

    public static function getFeedback($request){

        $content = View::render('pages/feedback',[
            'items' => self::getFeedbackItems($request, $pagination),
            'pagination' => parent::getPagination($request, $pagination)
        ]);

        return parent::getPage('Feedback', $content);
    }

    public static function saveFeedback($request){

        $postVars = $request->getPostVars();

        $feedback = new EntityFeedback();

        $feedback-> name = $postVars['name'];
        $feedback-> message = $postVars['message'];
        $feedback->save();

        return self::getFeedback($request);
    }
}