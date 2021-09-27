<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Feedback{

    public $id;
    public $name;
    public $message;
    public $date;

    public function save(){

        $this->date = date('Y-m-d H:i:s');

        $thisid= (new Database ('feedback'))->insert([
            'name' => $this->name,
            'message' => $this->message,
            'date' => $this->date
        ]);

        return true;
    }

    public static function getFeedback($where =  null, $order = null, $limit = null, $fields = '*'){

        return(new Database('feedback'))-> select($where, $order, $limit, $fields);
    }
}