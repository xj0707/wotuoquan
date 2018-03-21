<?php
//注册接口
namespace app\api\controller;

use think\Db;

class Register{
    //注册
    public function register(){
        $user_phone=input('user_phone');
        $verifycode=input('verifycode');
        $pwd1=input('pwd1');
        $pwd2=input('pwd2');
        if(!$pwd1 || !$pwd2 || !$user_phone || !$verifycode){
            return json(['code'=>-2,'message'=>'参数不完整！']);
        }
        if(!preg_match("/^1[34578]\d{9}$/", $user_phone)){
            return json(['code'=>-3,'message'=>'电话号码格式不正确！']);
        }
        if($pwd1!=$pwd2){
            return json(['code'=>-4,'message'=>'两次输入的密码不一致！']);
        }
        $info=Db::name('user')->where('user_phone',$user_phone)->find();
        if($info){
            return json(['code'=>-5,'message'=>'该电话号码已经注册了！']);
        }
        $time=time();
        $rand=rand(10000,99999);
        $avatar='avatar.jpg';
        $data=[
            'user_phone'=>$user_phone,
            'user_pwd'=>md5($pwd1),
            'user_type'=>1,
            'create_time'=>$time,
            'user_name'=>$rand,
            'user_avatar'=>$avatar
        ];
        $res=Db::name('user')->insert($data);
        if($res){
            return json(['code'=>1,'message'=>'注册成功！']);
        }else{
            return json(['code'=>-1,'message'=>'注册失败！']);
        }
    }
}