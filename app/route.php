<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;


Route::rule('test/:id','admin/Test/read');
//Route::rule(':version/applogin/user_login','api/:version.appLogin/userLogin');
//Route::rule(':version/applogin/test','api/:version.appLogin/test');
//Route::rule(':version/applogin/out_login','api/:version.appLogin/outLogin');
//Route::rule(':version/home/get_home_lunbo','api/:version.home/getHomeLunbo');
//Route::rule(':version/home/get_water','api/:version.home/getWater');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
