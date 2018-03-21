<?php
namespace app\admin\controller;

use app\admin\controller\AdminCommon;
use think\Db;

class User extends AdminCommon{

    public function index(){
        $lists=Db::name('user')->where('user_status',1)->order("user_line desc")->paginate(20);
        $this->assign('lists',$lists);
        return $this->fetch();
    }

    //用户电话查询
    public function searchphone(){
        $phone=input('user_phone');
        $lists=Db::name('user')->where('user_status',1)->where('user_phone','like',"%$phone%")->order("user_line desc")->paginate(20);
        $this->assign('lists',$lists);
        return $this->fetch('index');
    }

    //用户名查询
    public function searchname(){
        $name=input('user_name');
        $lists=Db::name('user')->where('user_status',1)->where('user_name','like',"%$name%")->order("user_line desc")->paginate(20);
        $this->assign('lists',$lists);
        return $this->fetch('index');
    }
}