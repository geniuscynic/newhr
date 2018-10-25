<?php

namespace app\common\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'nickName|昵称'  =>  'require|max:10',
        'login|帐号' =>  'require|max:10',
        'password|密码' => 'require',
        'password2|重复密码' => 'require|confirm:password'
    ];


    protected $scene = [
        'login' => ['login','password'],
        'register'  =>  ['nickName','email','password','password2']
    ];

}   