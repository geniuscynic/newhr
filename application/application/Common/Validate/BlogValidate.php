<?php

namespace app\Common\validate;

use think\Validate;

class BlogValidate extends Validate
{
    protected $rule = [
        'title|标题'  =>  'require|max:10',
        'content|内容'  =>  'require|max:2000|different:0',
        'categoryId|板块'  =>  'require|max:10'
    ];


    protected $scene = [
        'add' => ['title', 'content', 'categoryId']
    ];

}   