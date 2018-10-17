<?php

namespace app\common\model;

use think\Model;
use think\Db;
use app\common\entity\Message;

class CodeTable extends Base
{
   
    public function getCodeTable() : array {
        
        $phones = db()->Table("codeTable")
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
        $phones = db()->Table("codeTable")->where(["type"=>$type])->select();
              
        //var_dump(Db::getLastSql());
        return $this->getCodeTableTree($phones);

    }
}