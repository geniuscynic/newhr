<?php

namespace app\Common\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'nickName|昵称'  =>  'require|max:10',
        'email|邮箱' =>  'email|require',
        'password|密码' => 'require',
        'password2|重复密码' => 'require|confirm:password'
    ];


    protected $scene = [
        'login' => ['email','password'],
        'register'  =>  ['nickName','email','password','password2']
    ];

}   