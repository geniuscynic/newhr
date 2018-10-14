<?php

namespace app\common\model;

use think\Model;
use think\Db;
use app\common\entity\Message;

class Resume extends Base
{
   
    public function checkPhoneExist($data) : Message {
        $validate = validate("ResumeValidate");

        $msg = new Message(Message::TYPE_EXIST, '');

        if(!$validate->scene('add')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            $phones = db("resume")
                        ->where($data)
                        ->select();
            
            
            //var_dump(Db::getLastSql());
            if($phones == null) {
                $msg = new Message(Message::TYPE_NOT_EXIST, '');
            }
        }

        return $msg;

    }

    public function registerResume($data) : Message {
        $validate = validate("ResumeValidate");

        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('register')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            $phone = ['phone' => $data['phone']];

            $phones = db("resume")
                        ->where($phone)
                        ->select();
            
            
            //var_dump(Db::getLastSql());
            if($phones != null) {
                $msg = new Message(Message::TYPE_EXIST, '手机号已经注册过');
                return $msg;
            }
            
            unset($data['password2']);
            unset($data['func']);

            $data['createDate'] = date('Y-m-d H-i-s');
            $data['updateDate'] = date('Y-m-d H-i-s');
            $data['status'] = 1;

            $id = db("resume")->insertGetId($data);
            if($id > 0) {
               // $msg = new Message(Message::TYPE_EXIST, '手机号已经注册过');
               $msg->SetResultValue($id);
            }
            else {
                $msg = new Message(Message::TYPE_FAILED, '保存失败，请稍后再试');
            }
        }

        return $msg;
    } 

    public function loginResume($data) : Message {
        $validate = validate("ResumeValidate");
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('login')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            
            unset($data['func']);
            $data['status'] = 1;

            $id = db("resume")
                ->where($data)
                ->select();

            if($id != null) {
               // $msg = new Message(Message::TYPE_EXIST, '手机号已经注册过');
               $msg->SetResultValue($id);
            }
            else {
                $msg = new Message(Message::TYPE_FAILED, '帐号或者密码错误');
            }
        }

        return $msg;
    }
}