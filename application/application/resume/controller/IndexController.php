<?php
namespace app\resume\controller;

use app\common\controller\BaseController;
use app\common\entity\Message;

class IndexController extends BaseController
{
   
    public function home() {
        
        //$this->getCodeTable();

        return $this->fetch();
    }

    public function handle() {
        $input = input('post.');
        
       // var_dump($input);
         $func = $input['func'];
        // var_dump($func);
       // $idn = $input['idn'];
        //$site = $input['site'];
        //$empType = $input['empType'];
        //$source = $input['source'];
        //return json_encode("N");
         return $this->$func($input);
         //return "N";
    }

    

    private function checkPhoneExist($input) {
        $phone = $input["phone"];

        $msg = model("Resume")->checkPhoneExist(['phone' => $phone]);

        return  json($msg);
    }

    private function registerResume($input) {
        //$phone = $input["phone"];

        $msg = model("Resume")->registerResume($input);

        // if($msg->getMessage() == Message::TYPE_SUCCESSFULLY) {
        //     //var_dump($msg->getResultValue()[0]);
        //     session("resumeId", $msg->getResultValue()[0]);
        // }

        return  json($msg);
    }

    private function loginResume($input) {
        $msg = model("Resume")->loginResume($input);
        //var_dump($msg->getMessage());
        if($msg->getType() == Message::TYPE_SUCCESSFULLY) {
            //var_dump($msg->getResultValue()[0]);
           // var_dump($msg);

            session("resume", $msg->getResultValue());

            //var_dump($msg);

            //$msg->SetResultValue(array());
        }
        
        return json($msg);
    }

    
    
    public function resource() {
        $input = input('get.');
        $func = $input['func'];
        return $this->$func($input);
    }

    private function GetCommonCode($input) {
        $array = $this->getCodeTable();

        $codeTable = array();
        foreach ($array as $key => $value){
            $type = $value['type'];
            $code = $value['code'];
            $name = $value['name'];
            $codeTable[$type][] = array("title" => $name, 'value' => $code);
        }

        return json($codeTable);
    }

    private function GetCity($input) {
        $country = $input["country"];
        $province = model("Province")->GetCity($country);
        return json($province);
    }

    private function GetCommonCode2($input) {
        $array = model("CodeTable")->getCodeTableTest();

        // $codeTable = array();
        // foreach ($array as $key => $value){
        //     $type = $value['type'];
        //     $code = $value['code'];
        //     $name = $value['name'];
        //     $codeTable[$type][] = array("title" => $name, 'value' => $code);
        // }
        //dump($array);
        
        return json($array);
    }
}
