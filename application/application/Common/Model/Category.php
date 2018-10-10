<?php

namespace app\Common\model;

use think\Model;
use think\Db;
use app\Common\Entity\Message;

class Category extends Base
{
    public function Add($data) : Message {
        $validate = validate("CategoryValidate");

        //$result = array();
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('add')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
 
            $data['createTime'] = date('Y-m-d H-i-s');
            $data['updateTime'] = date('Y-m-d H-i-s');
            $data['status'] = 'A';
            $data['seqNum'] = 1;

            if($this->IsExistCategory($data)) {
                $msg = new Message(Message::TYPE_FAILED, '板块名重复');

                return $msg;
            }
           // $result['msg'] = array();
           // $result['value'] = db('category')->insert($data);
           $msg = new Message(Message::TYPE_SUCCESSFULLY, '添加成功');
           $msg->SetResultValue(db('category')->insert($data));
           //$msg->setMessage('添加成功');
        }

        return $msg;
    }

    public function SaveCatetory($data) : Message {
        $validate = validate("CategoryValidate");

        //$result = array();
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('save')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            $category = db('category')
            ->where('name', $data['name'])
            ->where('id', '<>', $data['id'])
            ->find();
            if($category) {
                $msg = new Message(Message::TYPE_FAILED, '板块名重复');
                return $msg;
            }


           // $result['msg'] = array();
           // $result['value'] = db('category')->insert($data);
           $msg = new Message(Message::TYPE_SUCCESSFULLY, '更新成功');
           $msg->SetResultValue(db('category')->update($data));
           //$msg->setMessage('添加成功');
        }

        //dump($msg);
        return $msg;
    }

    public function GetCategoryTree() : array{
        $category = db('category')->order(['level','seqNum'])->select();
        $category = $this->getTree2($category);

        return $category;
    }

    public function GetCategory() : array {
        $category = db('category')->order(['level','seqNum'])->select();
        $category = $this->getTree($category,0);

        return $category;
    }

    public function IsExistCategory(array $data) : bool {
        $category = db('category')
                    ->where('name', $data['name'])
                    ->find();
      
        if($category == null) {
           
            return false;
        }
        //dump($user);
        return true;
    }
}