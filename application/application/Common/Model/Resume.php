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

    public function getResumeById($resumeId) : Message {
        
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

            $data['id'] = $resumeId;
            $data['status'] = 1;

            $resume = db("resume")
                ->field(['password'],true)
                ->where($data)
                ->select();

            if($resume != null) {
               //unset($resume['password']);
               // $msg = new Message(Message::TYPE_EXIST, '手机号已经注册过');
               //dump($resume);
               $resume = $this->getResumetDetail($resume);
              // dump($resume);
               $msg->SetResultValue($resume[0]);
            }
            else {
                $msg = new Message(Message::TYPE_FAILED, 'resume 不存在');
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
                ->select();

            if($resume != null) {
               //unset($resume['password']);
               // $msg = new Message(Message::TYPE_EXIST, '手机号已经注册过');
               //dump($resume);
               $resume = $this->getResumetDetail($resume);
              // dump($resume);
               $msg->SetResultValue($resume[0]);
            }
            else {
                $msg = new Message(Message::TYPE_FAILED, '帐号或者密码错误');
            }
        }

        return $msg;
    }

    private function getResumetDetail($resumeList) {
        //dump($resume);

        $ids = array_column($resumeList, "id");

        $quarters = db("quarters")
                    ->alias('q')
                    ->join("codetable c3", "q.code3 = c3.code and c3.type = '11' and c3.level = 3")
                    ->join("codetable c2", "q.code2 = c2.code and c2.type = '11' and c2.level = 2")
                    ->join("codetable c1", "q.code1 = c1.code and c1.type = '11' and c1.level = 1")
                    ->field(['resumeId','q.code1','q.code2','q.code3', 'c1.name'=>'name1', 'c2.name'=>'name2', 'c3.name'=>'name3'])
                    ->where([
                        "resumeId" => $ids
                    ])
                    ->select();

        //$resume['quarters'] = $quarters;
        //dump($resume);


        $skills = db("skill")
                    ->alias('s')
                    ->field(['resumeId','s.name','s.file1','s.file2'])
                    ->where([
                        "resumeId" => $ids
                    ])
                    ->select();

       // $resume['skill'] = $skill;


        $workExperiences = db("work_experience")
                    ->alias('s')
                    ->field(['resumeId','s.startDate','s.endDate','s.companyName','s.post','s.duty'])
                    ->where([
                        "resumeId" => $ids
                    ])
                    ->select();

       // $resume['workExperience'] = $workExperience;

        $trains = db("train")
                    ->alias('s')
                    ->field(['resumeId','s.startDate','s.endDate','s.school','s.career','s.desc'])
                    ->where([
                        "resumeId" => $ids
                    ])
                    ->select();

        //$resume['train'] = $train;

        $families = db("family")
                    ->alias('s')
                    ->field(['resumeId','s.name','s.relation','s.relatePhone','s.work'])
                    ->where([
                        "resumeId" => $ids
                    ])
                    ->select();

        //$resume['family'] = $family;

        
        foreach($resumeList as $key => $value) {
            $resumeList[$key]['quarters'] = array();
            foreach($quarters as $quarter) {
                if($value["id"] == $quarter['resumeId']) {
                    $resumeList[$key]['quarters'][]  = $quarter;
                }
            }

            $resumeList[$key]['skills'] = array();
            foreach($skills as $skill) {
                if($value["id"] == $skill['resumeId']) {
                    $resumeList[$key]['skills'][]  = $skill;
                }
            }

            $resumeList[$key]['workExperiences'] = array();
            foreach($workExperiences as $workExperience) {
                if($value["id"] == $workExperience['resumeId']) {
                    $resumeList[$key]['workExperiences'][]  = $workExperience;
                }
            }

            $resumeList[$key]['trains'] = array();
            foreach($trains as $train) {
                if($value["id"] == $train['resumeId']) {
                    $resumeList[$key]['trains'][]  = $train;
                }
            }

            $resumeList[$key]['families'] = array();
            foreach($families as $familiy) {
                if($value["id"] == $familiy['resumeId']) {
                    $resumeList[$key]['families'][]  = $familiy;
                }
            }
        }

        return $resumeList;
    }

    public function getResumeList() {
        // '简历ID',
        //     '姓名',
        //     '性别',
        //     '电话',
        //     '身份证号',
        //     '出生日期',
        //     '民族',
        //     '最高学历',
        //     '政治面貌',
        //     '工作年限',
        //     '现居地址',
        //     '求职状态',
        //     '到岗时间',
        //     '从事行业',
        //     '期望工作性质',
        //     '期望岗位',
        //     '期望薪资',
        //     '期望工作地点',
        //     '紧急联络人',
        //     '紧急联系电话',
        //     '个人证书技能',
        //     '最后更新时间'
        $resumeList = db("resume")
                ->field(
                    "id,name,sex,phone,cardno,birthday,
                     nation,educational,political,workingYear,
                     house,workingStatus,joinTime,industry,
                     workType, 
                     '' as quarter1,
                     '' as quarter2,
                     '' as quarter3,

                     salary,workingAddress,contractName,
                     contractPhone, '' as skill,
                     updateDate"
                )
                ->order(['updateDate' => 'desc', 'id' => 'desc'])
                ->paginate(15);

       // dump($resumeList->items());
        //dump($resumeList);
       // $ids = array_column($resumeList->items(), "id");
        //dump($ids);
        //$item = $resumeList->items();

        $item = $this->getResumetDetail($resumeList->items());
        $resumeList->set($item);
        //$resumeList->items()
        //dump(Db::getLastSql());
        //dump($resumeList);
        return $resumeList;
    }

    public function searchResume($data) {
        // '简历ID',
        //     '姓名',
        //     '性别',
        //     '电话',
        //     '身份证号',
        //     '出生日期',
        //     '民族',
        //     '最高学历',
        //     '政治面貌',
        //     '工作年限',
        //     '现居地址',
        //     '求职状态',
        //     '到岗时间',
        //     '从事行业',
        //     '期望工作性质',
        //     '期望岗位',
        //     '期望薪资',
        //     '期望工作地点',
        //     '紧急联络人',
        //     '紧急联系电话',
        //     '个人证书技能',
        //     '最后更新时间'
        $resumeList = db("resume")
                ->field(
                    "id,name,sex,phone,cardno,birthday,
                     nation,educational,political,workingYear,
                     house,workingStatus,joinTime,industry,
                     workType, 
                     '' as quarter1,
                     '' as quarter2,
                     '' as quarter3,

                     salary,workingAddress,contractName,
                     contractPhone, '' as skill,
                     updateDate"
                )
                ->where([
                    "id" => $data
                ])
                ->order('updateDate', 'desc')
                ->select();

       // dump($resumeList->items());
        //dump($resumeList);
        //$ids = array_column($resumeList, "id");
        //dump($ids);
        //$item = $resumeList->items();

        $resumeList = $this->getResumetDetail($resumeList);
       
        //$resumeList->items()
        //dump(Db::getLastSql());
        //dump($resumeList);
        return $resumeList;
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

            $data['updateDate'] = date('Y-m-d H:i:s');
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
            $data['updateDate'] = date('Y-m-d H:i:s');
            $result = db("resume")->update($data);
            
        }

        return $msg;
    }

    public function saveBasic3($data) : Message {
        $msg = new Message(Message::TYPE_SUCCESSFULLY, '');

        $data2['hobby'] = $data["hobby"];
        $data2['id'] = $data["id"];
        unset($data['hobby']);
        unset($data['id']);
            
        //dump($data2);
            db("skill")
                ->where([
                    "resumeId" => $data2['id']
                ])
                ->delete();
               // var_dump(Db::getLastSql());
            //dump($quartersTable);

//dump($data);
            db("skill")->insertAll($data);
            //var_dump(Db::getLastSql());
            $data['updateDate'] = date('Y-m-d H:i:s');
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