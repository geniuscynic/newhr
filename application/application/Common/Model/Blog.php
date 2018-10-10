<?php

namespace app\Common\model;

use think\Model;
use think\Db;
use app\Common\Entity\Message;

class Blog extends Base
{
    public function Add($data) : Message {
        $validate = validate("BlogValidate");

        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('add')->batch()->check($data)) {
            //dump($data);
            $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
           
           
            $data['createTime'] = date('Y-m-d H-i-s');
            $data['updateTime'] = date('Y-m-d H-i-s');
            $data['status'] = 'A';
            //$data['seqNum'] = 1;

            $tags = $data['tag']; 
           
            unset($data['tag']);
        
            //$result['msg'] = array();
           // $result['value'] = db('blog')->insert($data);
            $msg = new Message(Message::TYPE_SUCCESSFULLY, '添加成功');
            $blogId = db('blog')->insertGetId($data);
            //$msg->SetResultValue(db('blog')->insertGetId($data));

            model("tag")->Add($blogId, $tags); 
        }

        return $msg;
    }

    public function GetBlogList() {
        //$blog = db('blog')->order(['updateTime'=>'desc'])->select();
        //$category = $this->getTree($category,0);
        $blog = $this->with('category,tag')->order(['updateTime'=>'desc'])->select();

        //dump($blog);

        return $blog;
    }

    public function category()
    {
        return $this->belongsTo('category','categoryId', 'id');
    }

    public function tag()
    {
        return $this->belongsToMany('tag','blog_tag', 'tagId','blogId');
    }
}