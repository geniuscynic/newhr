<?php

namespace app\common\validate;

use think\Validate;

class CodeValidate extends Validate
{
    protected $rule = [
        'name|技能名'  =>  'require|max:10',
        'seqNum|排序' => 'require|integer'
    ];


    protected $scene = [
        'add' => ['name'],
        'save' => ['name','seqNum'],
    ];

}   