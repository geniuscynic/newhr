<?php

namespace app\common\model;

use think\Model;
use think\Db;

class Base extends Model
{
    
   /**
     * 递归实现无限极分类
     * @param $array 分类数据
     * @param $pid 父ID
     * @param $level 分类级别
     * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
     */

    private $list = array();

    protected function getTree($array, $pid){
        foreach ($array as $key => $value){
            if($value['parentId'] == $pid) {
                $this->list[] = $value;

                unset($array[$key]);

                $this->getTree($array, $value['id']);
            }
        }

        return $this->list;
    }

    protected function getTree2($array) :array{
        //dump($array);
        $this->list = array();
        $items = array();
        foreach($array as $value){
            $value['text'] = $value['name'];

            $items[$value['id']] = $value;
           
        }

        //dump($items);

        foreach ($items as $key => $value){
            if(isset($items[$value['parentId']])) {
                //dump($value);
                $items[$value['parentId']]['nodes'][] = &$items[$key]; 
            }
            else {
                
                $this->list[] = &$items[$key]; 
            }
        }

        return $this->list;
    }

    protected function getCodeTableTree($array) :array{
        //dump($array);
        
        $this->list = array();
        $items = array();
        foreach($array as $value){
            //$value['text'] = $value['name'];
            unset($value['id']);
            unset($value['type']);
            unset($value['level']);
            unset($value['seqNum']);
            $items[$value['code']] = $value;
           
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