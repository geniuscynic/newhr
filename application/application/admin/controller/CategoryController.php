<?php
namespace app\admin\controller;

use app\Common\controller\BaseController;
use app\Common\Entity\Message;


class CategoryController extends BaseController
{
   
    public function Add() {

        $input = input('post.');
        if(count($input) > 0) {
            $form = array(
                'name' => $input['categoryName'],
                'parentId' => $input['parentCategoryId'],
                'level' => $input['level'] + 1
             );

             $result = model("category")->Add($form);
            
             $this->setMessage($result);
            //$this->redirect('admin/category/add');
            
        }
       
       $this->showMenu();
       
        return $this->fetch();
    }

    public function List() {
        $result = model("category")->GetCategory();
        $this->assign('categoryList', $result);

    //   $this->showCategory();
        return $this->fetch();
    }

    public function Save() {
       
        $input = input('post.');

        $form = array(
            'name' => $input['name'],
            'seqNum' => $input['seqNum'],
            'id' => $input['id']
        );


        $result = model("category")->SaveCatetory($form);
        
           // return json_encode($result->getMessage(),JSON_UNESCAPED_UNICODE);
        
        //dump($result);
        //dump(json_encode($result));
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    protected function showMenu() {
        
        $result = model("category")->GetCategoryTree();
        $this->assign('category', json_encode($result,JSON_UNESCAPED_UNICODE));

       
       // 
   }

}
