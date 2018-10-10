<?php

namespace app\Common\validate;

use think\Validate;

class CategoryValidate extends Validate
{
    protected $rule = [
        'name|板块名'  =>  'require|max:10',
        'seqNum|排序' => 'require|integer'
    ];


    protected $scene = [
        'add' => ['name'],
        'save' => ['name','seqNum'],
    ];

}   