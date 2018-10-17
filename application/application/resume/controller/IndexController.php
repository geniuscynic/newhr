<?php
namespace app\resume\controller;

use app\common\controller\BaseController;
use app\common\entity\Message;

class IndexController extends BaseController
{
   
    public function home() {
        
        $codeTable = $this->getCodeTable();
        //$codeTableQuarters = $this->getCodeTableByType(11);

        $quartersCodeTable = $this->getCodeTableTree($codeTable["11"]);
        //dump($codeTable["11"]);
        foreach($codeTable["11"] as $key=>$value) {
            if( $value['parentCode'] != "0" ) {
                //dump($value);
                unset($codeTable["11"][$key]);
            }
            
        }

        $codeTable["11"] = array_values($codeTable["11"]);
        //$codeTable = array_values($codeTable);
        //var_dump($codeTable);

        //dump
        //unset($codeTable["11"]);
        $this->assign('codeTable', json_encode($codeTable,JSON_UNESCAPED_UNICODE));
        $this->assign('quartersCodeTable', $quartersCodeTable);
        //dump($quartersCodeTable);
        //var_dump(json_encode($quartersCodeTable,JSON_UNESCAPED_UNICODE));
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
            // var_dump($msg->getResultValue()[0]);

            session("resume", $msg->getResultValue()[0]);

            //var_dump($msg);

            //$msg->SetResultValue(array());
        }
        
        return json($msg);
    }

    private function submitBasic1($input) {
        //dump(session("resume1"));
        //dump(session("resume"));
        if(session("resume") != null  && isset(session("resume")['id'])) {
            $input['id'] = session("resume")['id'];
        }
        else {
            $msg = new Message(Message::TYPE_FAILED, '保存失败，请稍后再试');
            return json($msg);
        }

        $msg = model("Resume")->submitBasic1($input);
        //var_dump($msg->getMessage());
        
        return json($msg);
    }

    private function submitBasic2($input) {
        //dump(session("resume1"));
        //dump(session("resume"));
        if(session("resume") != null  && isset(session("resume")['id'])) {
            $input['id'] = session("resume")['id'];
        }
        else {
            $msg = new Message(Message::TYPE_FAILED, '保存失败，请稍后再试');
            return json($msg);
        }

        $msg = model("Resume")->submitBasic2($input);
        //var_dump($msg->getMessage());
        
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
