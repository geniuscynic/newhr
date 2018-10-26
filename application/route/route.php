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
       


Route::rule("/user/login", "admin/user/login", "POST|GET");
Route::rule("/user/logout", "admin/user/logout", "GET");

Route::group('admin', function () {
    Route::rule("/", "admin/index/home", "GET");

    
    Route::rule("/resume/list", "admin/resume/list", "GET");

    Route::rule("/resume/exportExcel", "admin/resume/exportExcel", "POST");
    Route::rule("/resume/exportWord", "admin/resume/exportWord", "POST");

    Route::rule("/resume/detail", "admin/resume/detail", "GET");
    Route::rule("/codetable/list", "admin/codetable/list", "GET|POST");
    Route::rule("/codetable/delete", "admin/codetable/delete", "POST");
    // Route::rule("/register", "admin/user/register", "POST|GET");

    // Route::rule("/user/list", "admin/user/list", "GET");

    // Route::rule("/category/add", "admin/category/add", "POST|GET");
    // Route::rule("/category/list", "admin/category/list", "GET");
    // Route::rule("/category/save", "admin/category/save", "POST");

    // Route::rule("/blog/add", "admin/blog/add", "POST|GET");
    // Route::rule("/blog/list", "admin/blog/list", "GET");
})->middleware('Auth');

Route::group('hire', function () {
    Route::rule("/", "hire/index/home", "POST|GET");
    Route::rule("/handle", "hire/index/handle", "POST");
    Route::rule("/resource", "hire/index/resource", "GET");
});


Route::group('resume', function () {
    Route::rule("/", "resume/index/home", "GET");
    Route::rule("/handle", "resume/index/handle", "POST");
    Route::rule("/resource", "resume/index/resource", "GET");
    Route::rule("/referralCode", "resume/index/referralCode", "GET");
    
});