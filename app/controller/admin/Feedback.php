<?php

namespace App\Controller\Admin;

use App\Util\View;
use \App\Model\Entity\Feedback as EntityFeedback;
use \WilliamCosta\DatabaseManager\Pagination;


class Feedback extends Page{

    public static function getFeedbackItems($request, &$pagination){

        $items = '';

        $quant = EntityFeedback::getFeedback(null,null,null,'Count(*) as quantity')->fetchObject() -> quantity;
        
        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams['page'] ?? 1;

        $pagination = new Pagination($quant, $actualPage, 4);

        $results = EntityFeedback::getFeedback(null, 'id DESC', $pagination->getLimit());

        while($feedback =  $results->fetchObject(EntityFeedback::class)){

            $items .= View::render('admin/modules/feedback/item',[
                'id' => $feedback->id,
                'name' => $feedback->name,
                'message' => $feedback->message,
                'date' => date('d/m/Y H:i:s', \strtotime($feedback->date))
            ]);
        }
        return $items; 
    }

    public static function getStatus($request){

        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Feedback saved');
                break;
            case 'updated': 
                return Alert::getSuccess('Feedback Updated');
                break;
            case 'deleted': 
                return Alert::getSuccess('Feedback deleted');
                break;
        }
    }

    public static function getFeedback($request){

        $content = View::render('admin/modules/feedback/index',[
            'items' => self::getFeedbackItems($request, $pagination),
            'pagination' => parent::getPagination($request, $pagination),
            'status' => self::getStatus($request)
        ]);

        return parent::getPanel('Feedback', $content, 'feedback');
    }

    public static function getNewFeedback($request){

        $content = View::render('admin/modules/feedback/form',[
            'title' => 'Add Feedback',
            'name' => '',
            'message' => '',
            'status' => self::getStatus($request)
        ]);

        return parent::getPanel('Have something to say?', $content, 'feedback');
    }

    public static function setNewFeedback($request){

        $postVars = $request ->getPostVars();

        $feedback = new EntityFeedback;
        $feedback->name = $postVars['name'] ?? '';
        $feedback->message = $postVars['message'] ?? '';
        $feedback->save();

        $request->getRouter()->redirect('/admin/feedback/'.$feedback->id.'/edit?status=created');
    }

    public static function getEditFeedback($request, $id){

        $feedback = EntityFeedback::getFeedbackById($id);

        if(!$feedback instanceof EntityFeedback){
            $request->getRouter()->redirect('/admin/feedback');
        }

        $content = View::render('admin/modules/feedback/form',[
            'title' => 'Edit Message',
            'name' => $feedback->name,
            'message' => $feedback->message,
            'status' => self::getStatus($request)
        ]);

        return parent::getPanel('Edit', $content, 'feedback');
    }

    public static function setEditFeedback($request, $id){

        $feedback = EntityFeedback::getFeedbackById($id);

        if(!$feedback instanceof EntityFeedback){
            $request->getRouter()->redirect('/admin/feedback');
        }

        $postVars = $request->getPostVars();

        $feedback ->name = $postVars['name'] ?? $feedback -> name;
        $feedback ->message = $postVars['message'] ?? $feedback -> message;
        $feedback -> edit();

        $request->getRouter()->redirect('/admin/feedback/'.$feedback->id.'/edit?status=updated');
    }

    public static function getDeleteFeedback($request, $id){

        $feedback = EntityFeedback::getFeedbackById($id);

        if(!$feedback instanceof EntityFeedback){
            $request->getRouter()->redirect('/admin/feedback');
        }

        $content = View::render('admin/modules/feedback/delete',[
            'name' => $feedback->name,
            'message' => $feedback->message
        ]);

        return parent::getPanel('Delete', $content, 'feedback');
    }

    public static function setDeleteFeedback($request, $id){

        $feedback = EntityFeedback::getFeedbackById($id);

        if(!$feedback instanceof EntityFeedback){
            $request->getRouter()->redirect('/admin/feedback');
        }

        $feedback->remove();

        $request->getRouter()->redirect('/admin/feedback?status=deleted');
    }
}