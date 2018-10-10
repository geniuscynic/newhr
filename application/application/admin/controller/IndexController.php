<?php
namespace app\admin\controller;

use app\Common\controller\BaseController;

class IndexController extends BaseController
{
   
    public function home() {
        
        return $this->fetch();
    }
}
