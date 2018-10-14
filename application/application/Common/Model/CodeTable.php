<?php

namespace app\common\model;

use think\Model;
use think\Db;
use app\common\entity\Message;

class CodeTable extends Base
{
   
    public function getCodeTable() : array {
        
        $phones = db()->Table("codeTable")->select();
              
        //var_dump(Db::getLastSql());
        return $phones;

    }

    public function getCodeTableTest() : array {
        
        $phones = db()->Table("codeTable")->where(["type"=>"11"])->select();
              
        
        //var_dump(Db::getLastSql());
        return $this->getCodeTableTree($phones);

    }
}