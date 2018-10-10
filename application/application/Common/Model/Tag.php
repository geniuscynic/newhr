<?php

namespace app\Common\model;

use think\Model;
use think\Db;
use app\Common\Entity\Message;

class Tag extends Base
{
    private static $db;
    protected static function init()
    {
        self::$db = Db::name('tag');
        //dump("aa");
    }

    public function Add($blogId, $tags) {
       
        //dump($this->db);
        $result = self::$db
                    ->where(['name'=> $tags])
                    ->select();

                    //dump($result);

        $blogTag = array();
        foreach($result as $tag) {
            //dump($tag);

            $blogTag[] = [
                'blogId' => $blogId,
                'tagId'=> $tag['id']
            ];

            $key = array_search($tag['name'], $tags); 
            unset($tags[$key]);
        }

        //dump($blogTag);
        //$insertTag = array();
        foreach($tags as $tag) {
            $insertTag = [
                'name' => $tag,
                'createTime' => date('Y-m-d H-i-s'),
                'updateTime' => date('Y-m-d H-i-s')
            ]; 

            //dump($insertTag);

           // $option = &(self::$db->getOptions(''));
           // unset($option);

            $tagId =  db('tag')->insertGetId($insertTag, false);

            
            $blogTag[] = [
                'blogId' => $blogId,
                'tagId'=> $tagId
            ];
        }

        db('blog_tag')->insertAll($blogTag);
        //dump($blogTag);
        //dump($insertTag);
        //return $msg;
    }

 
    public function GetAllTags() : array {
        $tags = self::$db
                    ->field(
                        'name as id,
                         name as text'
                    )
                    ->select();
       // dump($tags);
        return $tags;
    }
}