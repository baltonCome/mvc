<?php

namespace App\Controller\Pages;

use \App\Util\View;
use \App\Model\Entity\Feedback as EntityFeedback;

class Feedback extends Page{

    public static function getFeedbackItems(){

        $items = '';

        $results = EntityFeedback::getFeedback(null, 'id DESC');

        while($feedback =  $results->fetchObject(EntityFeedback::class)){

            $items .= View::render('pages/feedback/items',[
                'name' => $feedback->name,
                'message' => $feedback->message,
                'date' => date('d/m/Y H:i:s', \strtotime($feedback->date))
            ]);
        }
        return $items; 
    }

    public static function getFeedback(){

        $content = View::render('pages/feedback',[
            'items' => self::getFeedbackItems()
        ]);

        return parent::getPage('Feedback', $content);
    }

    public static function saveFeedback($request){

        $postVars = $request->getPostVars();

        $feedback = new EntityFeedback();

        $feedback-> name = $postVars['name'];
        $feedback-> message = $postVars['message'];
        $feedback->save();
        return self::getFeedback();
    }
}