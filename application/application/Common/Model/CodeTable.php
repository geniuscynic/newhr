<?php

namespace app\common\model;

use think\Model;
use think\Db;
use app\common\entity\Message;

class CodeTable extends Base
{
    public function Add($data) : Message {
        $validate = validate("CodeValidate");

        //$result = array();
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('add')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            $codeTable = db("codetable")->where(['type'=> $data['type']])->select();
            $len = count($codeTable);

            

            if($this->IsExistCode($data)) {
                $msg = new Message(Message::TYPE_FAILED, '技能名重复');

                return $msg;
            }

            $data['code'] = str_pad(($len + 1),2, '0', STR_PAD_LEFT);
            $data['level'] =1;
            $data['parentCode'] = '';
            $data['seqNum'] = 100;

           // $result['msg'] = array();
           // $result['value'] = db('category')->insert($data);
           $msg = new Message(Message::TYPE_SUCCESSFULLY, '添加成功');
           $msg->SetResultValue(db('codetable')->insert($data));
           //$msg->setMessage('添加成功');
        }

        return $msg;
    }

    public function DeleteCodeTable($data) : Message {
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '删除成功');
        db('codetable')->where($data)->delete();

        return $msg;
    }

    public function IsExistCode(array $data) : bool {
        $category = db('codetable')
                    ->where($data)
                    ->find();
      
        if($category == null) {
           
            return false;
        }
        //dump($user);
        return true;
    }

    public function getCodeTable() : array {
        
        $phones = db()->Table("codetable")
                        ->field([
                        'code' => 'value',
                        'name' => 'title',
                        'type',
                        'parentCode'])
            //->where(["type", "<>", "11"])
            ->select();
              
            $list = array();
            foreach ($phones as $key => $value) {
                $type = $value["type"];
                unset($value["type"]);
                $list[$type][] = $value;
                //unset($list[$value["type"]]['type']);
            }
        //var_dump(Db::getLastSql());
        return $list;

    }

    public function getCodeTableByType($type) : array {
        $phones = db()->Table("codetable")->where(["type"=>$type])->select();
              
        //var_dump(Db::getLastSql());
        return $this->getCodeTableTree($phones);

    }
}