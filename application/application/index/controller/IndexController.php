<?php
namespace app\index\controller;
use app\common\controller\BaseController;

class IndexController extends BaseController
{
    public function index()
    {
        $codeTable = $this->getCodeTable();
        //$codeTableQuarters = $this->getCodeTableByType(11);

        $quartersCodeTable = $this->getCodeTableTree($codeTable["11"]);
        unset($codeTable["11"]);

        
        $this->assign('codeTable', json_encode($codeTable,JSON_UNESCAPED_UNICODE));
        $this->assign('quartersCodeTable', $quartersCodeTable);
        return $this->fetch();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
