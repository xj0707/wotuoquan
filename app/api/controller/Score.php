<?php
namespace app\api\controller;

use think\Db;
use think\Session;

class Score extends CheckSession
{

    //获取我的积分
    public  function  getScore(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $uid=Session::get('user_id','api');

        $user_id=input('user_id/d');
        if(!$user_id){
            return json(['code'=>-1,'message'=>'参数不完整']);
        }
                if($uid != $user_id){
            return json(['code'=>-2,'message'=>'发生异常错误，用户ID不匹配,用户ID为:'.$user_id.'sessionId为:'.$uid]);
        }
        $info=Db::name('user')->where('user_id',$user_id)->find();
        if(!$info){
            return json(['code'=>-3,'message'=>'没有找到该玩家']);
        }
        $ainfo=Db::name('address')->where('user_id',$user_id)->where('address_type',1)->find();
        if($ainfo){
            $address_id=$ainfo['id'];
        }else{//没有默认地址的时候
            $ainfo=Db::name('address')->where('user_id',$user_id)->where('address_type',0)->find();
            $address_id=$ainfo['id'];
        }
        $data=[
            'score'=>$info['user_integral'],
            'address_id'=>$address_id
        ];
        return json(['code'=>1,'message'=>'操作成功','data'=>$data]);
    }

    //积分商城
    public function scoreShop(){
        //        if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }
        $infos=Db::name('scoreProduct')->field('id,score_product as s_pname,score_url,score_norms,consume_score,score_num')->order('consume_score asc')->select();
        return json(['code'=>1,'message'=>'操作成功','data'=>$infos]);
    }

    //积分兑换
    public function useScore(){
                if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $user_id=input('user_id/d');
        $p_id=input('p_id/d');
        $address_id=input('address_id/d');
        if(!$user_id || !$address_id || !$p_id){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        $uid=Session::get('user_id','api');
                if($uid != $user_id){
            return json(['code'=>-2,'message'=>'发生异常错误，用户ID不匹配,用户ID为:'.$user_id.'sessionId为:'.$uid]);
        }
        $uinfo=Db::name('user')->where('user_id',$user_id)->find();
        if(!$uinfo){
            return json(['code'=>-3,'message'=>'不存在该用户，用户被删除！']);
        }
        $pinfo=Db::name('scoreProduct')->where('id',$p_id)->find();
        if(!$pinfo){
            return json(['code'=>-4,'message'=>'没得该奖品！']);
        }
        if($pinfo['score_num']<=0){
            return json(['code'=>-5,'message'=>'该奖品已经售空了！']);
        }
        $ainfo=Db::name('address')->where('id',$address_id)->find();
        if(!$ainfo){
            return json(['code'=>-6,'message'=>'地址有误！']);
        }
        if($ainfo['user_id'] != $user_id){
            return json(['code'=>-7,'message'=>'异常错误，地址ID和用户的ID不匹配！']);
        }
        if($uinfo['user_integral']<$pinfo['consume_score']){
            return json(['code'=>-8,'message'=>'你的积分不足！']);
        }
        $time=time();
        $data=[
            'user_id'=>$user_id,
            'sp_id'=>$p_id,
            'u_addressid'=>$address_id,
            'usescore'=>$pinfo['consume_score'],
            'create_time'=>$time

        ];
        $score=$uinfo['user_integral']-$pinfo['consume_score'];
        // 启动事务
        Db::startTrans();
        try {
            $res=Db::name('useScore')->insert($data);
            $res=Db::name('user')->update(['user_integral'=>$score,'user_id'=>$user_id]);
            $res=Db::name('scoreProduct')->where('id',$p_id)->setDec('score_num');//自减1;
            // 提交事务
            Db::commit();
            return json(['code'=>1,'message'=>'操作成功！']);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code'=>-9,'message'=>'操作失败！']);
        }

    }


}