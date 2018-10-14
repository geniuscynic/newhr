<?php

namespace app\Common\validate;

use think\Validate;

class ResumeValidate extends Validate
{
    protected $rule = [
        'phone|手机号'  =>  'require|mobile',
        'cardno|身份证号' => 'require|idCard',
        'password|密码' => 'require|length:6,25',
        'password2|重复密码'=>'require|confirm:password'

    ];


    protected $scene = [
        'add' => ['phone'],
        'register' => ['phone','password','password2'],
        'login' => ['phone','password'],
    ];

}