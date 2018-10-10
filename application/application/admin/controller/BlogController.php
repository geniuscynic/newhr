<?php
namespace app\admin\controller;

use app\Common\controller\BaseController;

//require_once '/verdor/HTMLPurifier/HTMLPurifier.auto.php';

class BlogController extends BaseController
{
   
    public function Add() {

        $input = input('post.');
        if(count($input) > 0) {
            $form = array(
                'title' => $input['title'],
                'content' => $input['content'],
                'categoryId' => $input['categoryId'],
                'tag' => $input['tag'],
             );

             require_once(__DIR__ . '/../../../vendor/HTMLPurifier/HTMLPurifier.auto.php');
             $config = \HTMLPurifier_Config::createDefault();
           
             $purifier = new \HTMLPurifier($config);
             $form['content'] = $purifier->purify($form['content']);

             //dump($form);
             $result = model("blog")->Add($form);
             

             $this->setMessage($result);

           
        }

        $this->showMenu();
        return $this->fetch();
    }


    public function List() {
        $blogList = model("blog")->GetBlogList();
        $this->assign('blogList', $blogList);

       // $users = model("blog")->with('profile')->select();
        //dump($blogList);
        return $this->fetch();
    }

    protected function showMenu() {
        $result = model("category")->GetCategoryTree();
        $this->assign('category', json_encode($result,JSON_UNESCAPED_UNICODE));

        $result = model("tag")->GetAllTags();
        $this->assign('tag', json_encode($result,JSON_UNESCAPED_UNICODE));
   }
}
