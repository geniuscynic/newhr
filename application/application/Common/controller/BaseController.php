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
        $this->assign('default_dir', "/newhr/application/public/");
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
}
