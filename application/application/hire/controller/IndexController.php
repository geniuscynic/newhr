<?php
namespace app\hire\controller;

use app\Common\controller\BaseController;

class IndexController extends BaseController
{
   
    public function home() {
        
        return $this->fetch();
    }

    public function handle() {
        $input = input('post.');
        //$func = $input['func'];
       // $idn = $input['idn'];
        //$site = $input['site'];
        //$empType = $input['empType'];
        //$source = $input['source'];
return "N";
       // return $input();
    }

    private function CheckIdn($input) {
        return "N";
    }
}
