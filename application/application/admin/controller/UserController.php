<?php
namespace app\admin\controller;

use app\Common\controller\BaseController;
use app\Common\Entity\Message;

class UserController extends BaseController
{
    public function login()
    {
        
        //$this->display();
        //return 'aa';
        $input = input('post.');
        if(count($input) > 0) {
            $form = array(
               
                'email' => $input['email'],
                'password' => $input['password']
             );

             $result = model("user")->Login($form);
             if($result->getType() == Message::TYPE_FAILED) {
                $this->setMessage($result);
             }
             else {
                session('user', $result->getResultValue());
                $this->redirect(url('admin/index/home'));
             }
            // if(count($result['msg']) > 0) {
            //     $msg = new Message(Message::TYPE_FAILED, $result['msg']);
            //     $this->setMessage($msg);
            //     //dump($result);
            //     //$this->setErrorMessage($result['msg']);
            // }
            // else {
            //     session('user', $result['value']);

            //     $this->redirect(url('admin/index/home'));
            // } 
        }

        url('admin/index/home');
        return $this->fetch();
    }

    public function register()
    {
        //$this->display();
        //return 'aa';
        $input = input('post.');

        
        if(count($input) > 0) {
           
            $form = array(
                'nickName' => $input['nickName'],
                'email' => $input['email'],
                'password' => $input['password'],
                'password2' => $input['password2']
             );

            // dump($form);

            $result = model("user")->RegUser($form);
            $this->setMessage($result);
            
            //  if(count($result['msg']) > 0) {
            //     //dump($result);
            //     $msg = new Message(Message::TYPE_FAILED, $result['msg']);
            //     //$msg->setType(Message::TYPE_FAILED);
            //    // $msg->setMessage("注册成功");
            //    //dump($msg);
            //     $this->setMessage($msg);
            //  } 
            //  else {
            //     $msg = new Message(Message::TYPE_SUCCESSFULLY, "注册成功");
            //     //$msg->setType(Message::TYPE_SUCCESSFULLY);
            //     //$msg->setMessage("注册成功");
            //     $this->setMessage($msg);
            //     //dump($msg);
            //  }

            //dump($result);
        }

        return $this->fetch();
    }

    public function list() {
        return $this->fetch();
    }
}
