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
    
    private function submitBasic3($input) {
        //dump(session("resume1"));
        //dump(session("resume"));
        $resuemId = 0;
       
        if(session("resume") != null  && isset(session("resume")['id'])) {
            $input['id'] = session("resume")['id'];
            $resuemId = $input['id'];
        }
        else {
            $msg = new Message(Message::TYPE_FAILED, '保存失败，请稍后再试');
            //return json($msg);
        }

        $data = array();
        if($input["skill"] != "" && $input["skill"] != "无") {
            $data[] = array(
                'resumeId' =>$resuemId,
                "name" => $input["skill"],
                "file1" => $this->parseFileName($input["li1"]),
                "file2" => $this->parseFileName($input["li2"])
            );
        }

        if($input["skill2"] != "" && $input["skill2"] != "无") {
            $data[] = array(
                'resumeId' =>$resuemId,
                "name" => $input["skill2"],
                "file1" => $this->parseFileName($input["li3"]),
                "file2" => $this->parseFileName($input["li4"])
            );
        }

        if($input["skill3"] != "" && $input["skill3"] != "无") {
            $data[] = array(
                'resumeId' =>$resuemId,
                "name" => $input["skill2"],
                "file1" => $this->parseFileName($input["li5"]),
                "file2" => $this->parseFileName($input["li6"])
            );
        }

        $data['hobby'] = $input["hobby"];
        $data['id'] = $resuemId;
        //dump($data);
        $msg = model("Resume")->submitBasic3($data);
        //var_dump($msg->getMessage());
        
        return json($msg);
    }

    private function parseFileName($url) {
        if($url == "none") {
            return "";
        }

        $url = str_replace("\")", "", $url);
        $urls = explode("/", $url);

        return array_pop($urls);
    }

    public function uploadPhoto($input) {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        

        //$file->info["name"] = $file->info["name"] . ".jpg"; 
        //dump( $file);
        // 移动到框架应用根目录/uploads/ 目录下
        $maxSize = 1024* 1024 * 5;
        $info = $file->validate(['size'=> $maxSize])->rule(function () {
            $resuemId = 0;
            if(session("resume") != null  && isset(session("resume")['id'])) {
                $resuemId = session("resume")['id'];
            }

            return $resuemId . "/" .date('YmdHis');
        })->move(UPLOAD_FOLDER);
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            //echo $info->getExtension();
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            //echo $info->getSaveName();
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            //echo $info->getFilename(); 
            echo UPLOAD_FOLDER . "/" .str_replace("\\", "/", $info->getSaveName());
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
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
