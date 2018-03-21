<?php
//登录接口
namespace app\api\controller;

use think\Db;
use think\Request;
use think\Session;

class APPLogin{
    //用户登录
    public function userLogin(Request $request){
        $user_phone=input('post.user_phone');
        $user_pwd=input('post.user_pwd');
        if(!$user_phone || !$user_pwd){
            return json(['code'=>-2,'message'=>'电话或密码为空！']);
        }
        if(!preg_match("/^1[34578]\d{9}$/", $user_phone)){
            return json(['code'=>-4,'message'=>'电话号码有误！']);
        }
        $info=Db::name('user')->where('user_phone',$user_phone)->where('user_pwd',md5($user_pwd))->find();
        if($info){
            if($info['user_status']===0){
                return json(['code'=>-3,'message'=>'该用户已被禁用！']);
            }
            $ip=$request->ip();//获取ip地址
            $time=time();
            //更新记录用户登录ip已经登录时间
            $res=Db::name('user')->where('user_id',$info['user_id'])->update(['user_ip'=>$ip,'update_time'=>$time,'user_line'=>1]);
            //记录session
            Session::set('user_id',$info['user_id'],'api');
            Session::set('user',$info,'api');
            $data=['user_id'=>$info['user_id'],'user_type'=>$info['user_type']];
            return json(['data'=>$data,'code'=>1,'message'=>'登录成功！']);
        }else{
            return json(['code'=>-1,'message'=>'登录失败！']);
        }
    }
    //用户退出登录
    public function outLogin(){
        //$user_id=Session::get('user_id','api');
        $uid=input('user_id/d');
        if(!$uid){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
//        if($user_id != $uid){
//            return json(['code'=>-2,'message'=>'发生异常，传的ID和sessionId 不一致！']);
//        }
        $res=Db::name('user')->where('user_id',$uid)->update(['user_line'=>0]);
        Session::clear('api');
        return json(['code'=>1,'message'=>'退出成功！']);


    }

    //用户第三方登录
    public function otherLogin(Request $request){
        $openid=input('openid');
        if(!$openid){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        $info=Db::name('user')->where('openid',$openid)->find();
        if($info){
            //记录session
            Session::set('user_id',$info['user_id'],'api');
            Session::set('user',$info,'api');
            $data=['user_id'=>$info['user_id'],'user_phone'=>$info['user_phone'],'user_pwd'=>$info['user_pwd'],'user_type'=>$info['user_type']];
            return json(['data'=>$data,'code'=>1,'message'=>'登录成功！']);
        }else{
            $ip=$request->ip();//获取ip地址
            $arr=[3=>1,4=>1,5=>1,7=>1,8=>1];
            $rand=array_rand($arr,1);
            $str='';
             for ($i=0;$i<9;$i++){
                 $str.=mt_rand(0,9);
             }
            $phone='1'.$rand.$str;
            $data=[
                'user_phone'=>$phone,
                'user_pwd'=>md5('wtq123456'),
                'user_name'=>'wtq'.mt_rand(0000,9999),
                'user_type'=>2,
                'user_line'=>1,
                'user_status'=>1,
                'user_ip'=>$ip,
                'create_time'=>time(),
                'openid'=>$openid
            ];
            $id=Db::name('user')->insertGetId($data);
            if($id){
                //记录session
                Session::set('user_id',$id,'api');
                Session::set('user',$data,'api');
                $data=['user_id'=>$id,'user_phone'=>$phone,'user_pwd'=>md5('wtq123456'),'user_type'=>2];
                return json(['data'=>$data,'code'=>1,'message'=>'登录成功！']);
            }else{
                return json(['code'=>-1,'message'=>'登录失败！']);
            }

        }


    }





    //测试session是否有用
    public function test(){
       if(!Session::has('user_id','api')){
           return json(['code'=>-1,'message'=>'你还没有登录！']);
       }else{
           return json(['code'=>1,'message'=>'你是登录状态！']);
       }
    }
}