<?php

namespace app\Common\model;

use think\Model;
use think\Db;
use app\Common\Entity\Message;

class User extends Base
{
    public function Login(array $data) : Message {
        $validate = validate("UserValidate");

       // $result = array();
       $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('login')->batch()->check($data)) {
            //dump($data);
            //$result['msg'] = $validate->getError();
           // $result['value'] = 0;

            $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
           
            //$result['msg'] = array();

            $data['status'] = 'A';
            $msg->SetResultValue(db('user')->where($data)->select());

           // dump(db('user')->where($data)->select());
           // dump( $msg->getResultValue());
            if(count($msg->getResultValue()) ==0 ) {
                //$result['msg'][] = '帐号或者密码错误';
                $msg = new Message(Message::TYPE_FAILED, '帐号或者密码错误');
            }
            
        }

        return $msg;
    }

    public function RegUser(array $data) : Message {
        $validate = validate("UserValidate");

        //$result = array();
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('register')->batch()->check($data)) {
            //dump($data);
            //$result['msg'] = $validate->getError();
            //$result['value'] = 0;
            $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            unset($data['password2']);

            if($this->CheckSameUser($data)) {
                //$result['msg'][] = "邮箱或者昵称重复";
                //$result['value'] = 0;
                $msg = new Message(Message::TYPE_FAILED, '邮箱或者昵称重复');

                return $msg;
            }

            $data['createTime'] = date('Y-m-d H-i-s');
            $data['updateTime'] = date('Y-m-d H-i-s');
            $data['status'] = 'A';

           // $result['msg'] = array();
           $msg->SetResultValue(db('user')->insert($data));

            $msg->setMessage('注册成功');
        }

       
        return $msg;
    }

    public function CheckSameUser(array $data) : bool {
        $user = db('user')
                    ->whereOr('email', $data['email'])
                    ->whereOr('nickname', $data['nickName'])
                    //->fetchSql(true)
                    ->find();
       
       
        if($user == null) {
           
            return false;
        }
        //dump($user);
        return true;
    }
}