<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::rule("/", "Index/Index/Index", "POST|GET");



Route::group('admin', function () {
    Route::rule("/home", "admin/index/home", "GET");

    Route::rule("/login", "admin/user/login", "POST|GET");
    Route::rule("/register", "admin/user/register", "POST|GET");

    Route::rule("/user/list", "admin/user/list", "GET");

    Route::rule("/category/add", "admin/category/add", "POST|GET");
    Route::rule("/category/list", "admin/category/list", "GET");
    Route::rule("/category/save", "admin/category/save", "POST");

    Route::rule("/blog/add", "admin/blog/add", "POST|GET");
    Route::rule("/blog/list", "admin/blog/list", "GET");
});

Route::group('hire', function () {
    Route::rule("/home", "hire/index/home", "POST|GET");
    Route::rule("/handle", "hire/index/handle", "POST|GET");
});


