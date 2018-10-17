<?php

namespace app\Common\validate;

use think\Validate;

class ResumeValidate extends Validate
{
    protected $rule = [
        'phone|手机号'  =>  'require|mobile',
        'cardno|身份证号' => 'require|idCard',
        'password|密码' => 'require|length:6,12',
        'password2|重复密码'=>'require|confirm:password',

       
        'name|姓名'=> 'require|length:2,6',
        'sex|性别'=> 'require',
        'birthday|出生日期'=> 'require',
        'nation|民族'=> 'require',
        'educational|最高学历'=> 'require',
        'political|政治面貌'=> 'require',
        'house|现居地址'=> 'require',
        'contractName|紧急联系人'=> 'require',
        'contractPhone|紧急联系电话'=> 'require|mobile',

        'workingYear|工作年限'=> 'require',
        'workingStatus|求职状态'=> 'require',
        'joinTime|到岗时间'=> 'require',
        'workType|期望工作性质'=> 'require',
        'industry|从事行业'=> 'require',
        'quarters|期望工作岗位'=> 'require',
        'salary|期望薪资'=> 'require',
        'workingAddress|期望工作地点'=> 'require'
    ];


    protected $scene = [
        'add' => ['phone'],
        'register' => ['phone','password','password2'],
        'login' => ['phone','password'],
        'basic1' => [ 'cardno','name','sex','birthday', 'nation', 'educational', 'political', 'house', 'contractName', 'contractPhone'],
        'basic2' => [ 'workingYear', 'workingStatus', 'joinTime', 'workType', 'industry', 'quarters', 'salary', 'workingAddress'],
    ];

}