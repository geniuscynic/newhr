<?php
namespace app\Common\controller;

use think\Controller;
use app\Common\Entity\Message;

class BaseController extends Controller
{
    protected function initialize()
    {
       // dump(request()->action());
        $this->show();
    }
    
    protected function show() {
        //$msg = new Message(Message::TYPE_DEFAULT, '');
        //$this->setMenuActive();
       // $this->setMessage($msg);
        //dump(request());
        //Request::instance();
        //var_dump(APP_PATH);
        $this->assign('default_dir', RESOURCE_FOLDER);
    }

    protected function setMessage(Message $message) {
        $this->assign('message', $message);
        
       // $request= Request::instance();
    }

    private static $isActive = false;
    private static $action = "";
    private static function isActive(string $controller)  {
        $request = request();
        self::$action = $request->action();

        if($request->controller() == $controller)  {
            self::$isActive = true;
        }
        else {
            self::$isActive = false;
        }
    }

    public static function setMenuActive(string $controller) {
       // $request= Request::instance();
        //dump($request);
        self::isActive( $controller);
        if(self::$isActive){
            return "active";
        }
        else {
            return "collapsed";
        }

        
        //dump($request->module());
        //controller
        //action
    }

    public static function setSubMenuShow() {
        
         if(self::$isActive) {
             return "show";
         }
         else {
             return "";
         } 
     }

     public static function setSubMenuActive(string $action) {
        
        if(self::$isActive && self::$action == $action) {
            return "active";
        }
        else {
            return "";
        } 
    }

    protected function getCodeTable() : array {
        //cache('codeTable', null);
        if(!cache('codeTable')) {
            $codeTable = model("CodeTable")->getCodeTable();
            cache('codeTable', $codeTable, 36000);
        }

        //dump(cache('codeTable'));
        return cache('codeTable');
        //model("CodeTable")->getCodeTable();
    }

    protected function getCodeTableByType($type) : array {
        //cache('codeTable', null);
        $key = 'codeTable'.$type;
        if(!cache($key)) {
            $codeTable = model("CodeTable")->getCodeTableByType($type);
            cache($key, $codeTable, 36000);
        }

        //dump(cache('codeTable'));
        return cache($key);
        //model("CodeTable")->getCodeTable();
    }

    protected function getCodeTableTree($array) :array{
        //dump($array);
        
        $this->list = array();
        $items = array();
        foreach($array as $value){
            //$value['text'] = $value['name'];
            //unset($value['id']);
            //unset($value['type']);
            //unset($value['level']);
            //unset($value['seqNum']);
            $items[$value['value']] = $value;
           
        }

        //dump($items);

        foreach ($items as $key => $value){
           // dump($items[$value['parentCode']]);

            if(isset($items[$value['parentCode']])) {
                //var_dump($value);
                unset($items[$key]['parentCode']);
                $items[$value['parentCode']]['sub'][] = &$items[$key]; 

            }
            else {
                unset($items[$key]['parentCode']);
                $this->list[] = &$items[$key]; 
            }
        }

        return $this->list;
    }
}
