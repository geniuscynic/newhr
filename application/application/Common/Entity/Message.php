<?php
namespace app\Common\Entity;

use think\Controller;

class Message
{
    public  const  TYPE_DEFAULT = 0;
    public  const  TYPE_SUCCESSFULLY = 1;
    public  const  TYPE_FAILED = 2;
    
   
    public $type = 0;
    public $message = array();
    public $resultValue = array();

    // function __construct() {
    //     $this->setType($this->TYPE_DEFAYLT);
    //     $this->setMessage('');
    // }

    function __construct($type, $message) {
        $this->setType($type);
        $this->setMessage($message);
    }

    public function setType(int $type) {
        $this->type = $type;
    }

    public function getType():int {
        return $this->type;
    }

    public function setMessage($message) {
        $type = gettype($message);
       
        switch($type) {
            case "string":
                $this->message[] = $message;
                break;
    
            case "array":
                $this->message = $message;
                break;
        }
               
    }

    public function getResultValue() : array
    {
        return $this->resultValue;
    }

    public function SetResultValue($data) {
        $type = gettype($data);
       
        switch($type) {
            case "string":
                $this->resultValue[] = $data;
                break;
    
            case "array":
                $this->resultValue = $data;
                break;
        }

       
    }

    public function getMessage() : array {
       
        return $this->message;
    }

    public function __call($name, $arguments) 
    {
        switch ($name) {
            case 'setMessage':
               
                break;
            
            default:
                # code...
                break;
        }
      
    }
}
