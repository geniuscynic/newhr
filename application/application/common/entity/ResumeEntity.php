<?php
namespace app\common\entity;

use think\Controller;

class ResumeEntity
{
    public $phone = "";
    public $password = "";
    public $createDate = "";//date('Y-m-d H-i-s');
    public $updateDate = "";
    public $status = "1";
    public $cardno = "";
    public $name = "";
    public $sex = "";
    public $birthday = null;
    public $nation = "";
    public $educational = "";
    public $political = "";
    public $house = "";
    public $contractName = "";
    public $contractPhone = "";
    public $hobby = "";
    public $workingYear = "";
    public $workingStatus = "";
    public $joinTime = "";
    public $workType = "";
    public $industry = "";
    public $salary = "";
    public $workingAddress = "";

    function __construct() {
        $this->createDate = date('Y-m-d');
        $this->updateDate = date('Y-m-d');
    }
}
