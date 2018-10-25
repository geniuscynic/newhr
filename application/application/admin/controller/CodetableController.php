<?php
namespace app\admin\controller;

use app\common\controller\BaseAdminController;

//require_once '/verdor/HTMLPurifier/HTMLPurifier.auto.php';

class CodetableController extends BaseAdminController
{

    public function List() {
        $input = input('post.');
        if(count($input) > 0) {
            $form = array(
                'name' => $input['skill'],
                'type' => '09'
             );

             $result = model("CodeTable")->Add($form);
            
             $this->setMessage($result);
        }
      
        $resume = model("CodeTable")->getCodeTableByType('09');

        $this->assign('codeTableList', $resume);
        
        //dump($resume);
        return $this->fetch();
    }

    public function Delete() {
        $input = input('post.');

        $form = array(
            'name' => $input['name'],
            'type' => '09'
         );

        $result = model("CodeTable")->DeleteCodeTable($form);

        return json($result);
    }
}
