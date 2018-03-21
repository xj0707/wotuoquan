<?php
namespace app\admin\controller;

use think\Db;

class Pay extends AdminCommon{
    //支付信息
    public function index(){
        $lists=Db::name('payinfo')->alias('p')->join('user u','p.userid=u.user_id')->order('p.id desc')->paginate(20);
        $total=Db::name('payinfo')->count();
        $wx=Db::name('payinfo')->where('paystate',1)->count();
        $ali=Db::name('payinfo')->where('paystate',2)->count();
        $this->assign('total',$total);
        $this->assign('wx',$wx);
        $this->assign('ali',$ali);
        $this->assign('lists',$lists);
        return $this->fetch();
    }


}