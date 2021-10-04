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

        $this->id = (new Database ('feedback'))->insert([
            'name' => $this->name,
            'message' => $this->message,
            'date' => $this->date
        ]);
        return true;
    }

    public static function getFeedbackById($id){

        return self::getFeedback('id = '.$id)->fetchObject(self::class);
    }

    public static function getFeedback($where =  null, $order = null, $limit = null, $fields = '*'){

        return(new Database('feedback'))-> select($where, $order, $limit, $fields);
    }

    public function edit(){

        return (new Database ('feedback'))->update('id = '.$this->id, [
            'name' => $this->name,
            'message' => $this->message
        ]);
    }

    public function remove(){

        return (new Database ('feedback'))->delete('id = '.$this->id);
    }
}