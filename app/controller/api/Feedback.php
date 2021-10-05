<?php

namespace App\Controller\Api;

use \App\Model\Entity\Feedback as EntityFeedback;
use \WilliamCosta\DatabaseManager\Pagination;

class Feedback extends Api{


    public static function getFeedbackItems($request, &$pagination){

        $items = [];

        $quant = EntityFeedback::getFeedback(null,null,null,'Count(*) as quantity')->fetchObject() -> quantity;
        
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        $pagination = new Pagination($quant, $actualPage, 4);

        $results = EntityFeedback::getFeedback(null, 'id DESC', $pagination->getLimit());

        while($feedback =  $results->fetchObject(EntityFeedback::class)){

            $items[] = [
                'id' => (int)$feedback ->id,
                'name' => $feedback->name,
                'message' => $feedback->message,
                'date' => $feedback->date
            ];
        }
        return $items; 
    }

    //this method returns all the feedbacks
    public static function getFeedback($request){

        return [
            'feedback' => self::getFeedbackItems($request, $pagination),
            'pages' => parent::getPagination($request, $pagination)
        ];
    }

    //this one returns only one selected feedback
    public static function getSelectedFeedback($request, $id){

        if(!\is_numeric($id)){
            throw new \Exception("Invalid ID ".$id, 400);   
        }

        $feedback = EntityFeedback::getFeedbackById($id);
        if(!$feedback instanceof EntityFeedback){
            throw new \Exception("Unable to find requested feedback ".$id, 404);
        }
        return[
            'id' => (int)$feedback ->id,
            'name' => $feedback->name,
            'message' => $feedback->message,
            'date' => $feedback->date
        ];
    }

    public static function setNewFeedback($request){

        $postVars = $request->getPostVars();

        if(!isset($postVars['name']) or !isset($postVars['message'])){
            throw new \Exception("Name and message fields are required", 400);
        }

        $feedback = new EntityFeedback;
        $feedback ->name  = $postVars['name'];
        $feedback ->message  = $postVars['message'];
        $feedback ->save();

        return[
            'id' => (int)$feedback ->id,
            'name' => $feedback->name,
            'message' => $feedback->message,
            'date' => $feedback->date
        ];
    }

    public static function setEditFeedback($request, $id){

        $postVars = $request->getPostVars();

        if(!isset($postVars['name']) or !isset($postVars['message'])){
            throw new \Exception("Name and message fields are required", 400);
        }

        $feedback = EntityFeedback::getFeedbackById($id);

        if(!$feedback instanceof EntityFeedback){
            throw new \Exception("Unable to find requested feedback ".$id, 404);
        }

        $feedback ->name  = $postVars['name'];
        $feedback ->message  = $postVars['message'];
        $feedback ->edit();

        return[
            'id' => (int)$feedback ->id,
            'name' => $feedback->name,
            'message' => $feedback->message,
            'date' => $feedback->date
        ];
    }

    public static function setDeleteFeedback($request, $id){

        $feedback = EntityFeedback::getFeedbackById($id);

        if(!$feedback instanceof EntityFeedback){
            throw new \Exception("Unable to find requested feedback ".$id, 404);
        }

        $feedback ->remove();

        return[
            'success' => true
        ];
    }
}