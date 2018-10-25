<?php
namespace app\common\controller;

use app\common\controller\BaseController;
use think\Controller;
use app\common\Entity\Message;

class BaseAdminController extends BaseController
{
   
    
    protected function show() {
        parent::show();
        //$this->assign('default_dir', RESOURCE_FOLDER);
        //dump(session('user'));
        $this->assign('user', session('user'));
    }


}
