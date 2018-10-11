<?php

namespace app\common\model;

use think\Model;
use think\Db;

class Province extends Base
{
    //private static $db;
    // protected static function init()
    // {
    //     //self::$db = Db::name('tag');
    //     //dump("aa");
    // }

    public function GetProvince() : array {
        $tags = db("province")
                    ->select();
       // dump($tags);
        return $tags;
    }

    public function GetCity($pid) : array {
        $tags = db("city")
                    ->alias(['city' => 'c'])//, 'province' => 'p'])
                    //->join("province", 'c.p_id = p.code')
                    ->where('c.p_id', $pid)
                    ->field('c.*')
                    ->select();
       // dump($tags);
        return $tags;
    }
}