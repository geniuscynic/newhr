<?php
namespace app\index\controller;
use app\Common\controller\BaseController;

class IndexController extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
