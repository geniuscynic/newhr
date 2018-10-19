<?php

namespace app\common\model;

use think\Model;
use think\Db;
use app\common\entity\Message;
use app\common\entity\ResumeEntity;

class Resume extends Base
{
   
    public function checkPhoneExist($data) : Message {
        $validate = validate("ResumeValidate");

        $msg = new Message(Message::TYPE_EXIST, '');

        if(!$validate->scene('add')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            $phones = db("resume")
                        ->where($data)
                        ->select();
            
            
            //var_dump(Db::getLastSql());
            if($phones == null) {
                $msg = new Message(Message::TYPE_NOT_EXIST, '');
            }
        }

        return $msg;

    }

    public function registerResume($data) : Message {
        $validate = validate("ResumeValidate");

        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('register')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            $phone = ['phone' => $data['phone']];

            $phones = db("resume")
                        ->where($phone)
                        ->select();
            
            
            //var_dump(Db::getLastSql());
            if($phones != null) {
                $msg = new Message(Message::TYPE_EXIST, '手机号已经注册过');
                return $msg;
            }
            
            //unset($data['password2']);
            //unset($data['func']);

           // $data['createDate'] = date('Y-m-d H-i-s');
           // $data['updateDate'] = date('Y-m-d H-i-s');
           // $data['status'] = 1;
           $resume = new ResumeEntity();
           $resume->phone = $data["phone"];
           $resume->password = $data["password"];

           $resume = $this->object_to_array($resume);
            $id = db("resume")->insertGetId($resume);
            if($id > 0) {
               // $msg = new Message(Message::TYPE_EXIST, '手机号已经注册过');
               $msg->SetResultValue($id);
            }
            else {
                $msg = new Message(Message::TYPE_FAILED, '保存失败，请稍后再试');
            }
        }

        return $msg;
    } 

    public function loginResume($data) : Message {
        $validate = validate("ResumeValidate");
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('login')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            
            unset($data['func']);
            $data['status'] = 1;

            $resume = db("resume")
                ->field(['password'],true)
                ->where($data)
                ->find();

            if($resume != null) {
               //unset($resume['password']);
               // $msg = new Message(Message::TYPE_EXIST, '手机号已经注册过');
               //dump($resume);
               $resume = $this->getResumetDetail($resume);
              // dump($resume);
               $msg->SetResultValue($resume);
            }
            else {
                $msg = new Message(Message::TYPE_FAILED, '帐号或者密码错误');
            }
        }

        return $msg;
    }

    private function getResumetDetail($resume) {
        //dump($resume);

        $quarters = db("quarters")
                    ->alias('q')
                    ->join("codetable c", "q.code3 = c.code and c.type = '11' and c.level = 3")
                    ->field(['code1','code2','code3', 'c.name'])
                    ->where([
                        "resumeId" => $resume['id']
                    ])
                    ->select();

        $resume['quarters'] = $quarters;
        //dump($resume);


        $skill = db("skill")
                    ->alias('s')
                    ->field(['s.name','s.file1','s.file2'])
                    ->where([
                        "resumeId" => $resume['id']
                    ])
                    ->select();

        $resume['skill'] = $skill;
        return $resume;
    }

    public function submitBasic1($data) : Message {
        $validate = validate("ResumeValidate");
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('basic1')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
            unset($data['func']);
            $result = db("resume")->update($data);
            
        }

        return $msg;
    }

    public function submitBasic2($data) : Message {
        $validate = validate("ResumeValidate");
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        if(!$validate->scene('basic2')->batch()->check($data)) {
            //dump($data);
           // $result['msg'] = $validate->getError();
           // $result['value'] = 0;
           $msg = new Message(Message::TYPE_FAILED, $validate->getError());
        }
        else {
           // $quarter = $data['quarters'];
           // $quarters = explode(",", $quarter);
            //dump($quarters);
            foreach(explode(",",  $data['quarters']) as $quarter) {
                //dump($quarter);
                $quarters = explode("_", $quarter);
                $quartersTable[] = array(
                    "resumeId" => $data['id'],
                    "code1" => $quarters[0],
                    "code2" => $quarters[1],
                    "code3" => $quarters[2]
                ); 
            }
            db("quarters")
                ->where([
                    "resumeId" => $data['id']
                ])
                ->delete();

            //dump($quartersTable);
            db("quarters")->insertAll($quartersTable);

            unset($data['func']);
            unset($data['quarters']);
            $result = db("resume")->update($data);
            
        }

        return $msg;
    }

    public function submitBasic3($data) : Message {
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        $data2['hobby'] = $data["hobby"];
        $data2['id'] = $data["id"];
        unset($data['hobby']);
        unset($data['id']);
            
            db("skill")
                ->where([
                    "resumeId" => $data2['id']
                ])
                ->delete();

            //dump($quartersTable);

//dump($data);
            db("skill")->insertAll($data);
            $result = db("resume")->update($data2);
            
        

        return $msg;
    }

    function submitWork($data) : Message {
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        //dump($data);
    
            db("work_experience")
                ->where([
                    "resumeId" => $data[0]['resumeId']
                ])
                ->delete();


            db("work_experience")->insertAll($data);
           // $result = db("resume")->update($data2);
            
        

        return $msg;
    }

    function submitTrain($data) : Message {
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        //dump($data);
    
            db("train")
                ->where([
                    "resumeId" => $data[0]['resumeId']
                ])
                ->delete();


            db("train")->insertAll($data);
           // $result = db("resume")->update($data2);
            
        

        return $msg;
    }

    function submitFamily($data) : Message {
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        //dump($data);
    
            db("family")
                ->where([
                    "resumeId" => $data[0]['resumeId']
                ])
                ->delete();


            db("family")->insertAll($data);
           // $result = db("resume")->update($data2);
            
        

        return $msg;
    }
}