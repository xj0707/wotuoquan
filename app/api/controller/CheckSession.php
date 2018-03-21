<?php
namespace app\api\controller;

use think\Controller;
use think\Session;

class CheckSession extends Controller {
    //检查session
    public function _initialize()
    {
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }

    }

}