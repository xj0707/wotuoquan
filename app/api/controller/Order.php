<?php
namespace app\api\controller;

use think\Db;
use think\Session;

class Order extends CheckSession{
    //订单信息
    public function orderInfo(){
//        if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }
//        $uid=Session::get('user_id','api');
        $user_id=input('user_id/d');
        if(!$user_id){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
//        if($uid != $user_id){
//            return json(['code'=>-2,'message'=>'用户ID和session id 不一致！']);
//        }
        $pids=Db::name('order')->field('order_id,product_id,buy_num,product_totalprice,order_state,order_time')->where('user_id',$user_id)->order('order_id desc')->limit(20)->select();
       if(count($pids)){
           $data=array();
           foreach($pids as $key=>$pid){
               $data[$key]['order_id']=$pid['order_id'];
               $data[$key]['product_id']=$pid['product_id'];
               $pinfo=Db::name('productList')->field('product_name,product_describe')->where('id',$pid['product_id'])->find();
               $data[$key]['product_name']=$pinfo['product_name'];
               $data[$key]['product_describe']=$pinfo['product_describe'];
               $data[$key]['buy_num']=$pid['buy_num'];
               $data[$key]['totalprice']=$pid['product_totalprice'];
               $data[$key]['o_state']=$pid['order_state']==0?'否':'是';
               $data[$key]['o_time']=date("Y-m-d H:i:s",$pid['order_time']);
           }
           return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
       }else{
           return json(['code'=>-3,'message'=>'还没有订单！']);
       }
    }
    //用户确认收货
    public function orderState(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $oid=input('orderId/d');
        $user_id=input('user_id/d');
        $uid=Session::get('user_id','api');
        if(!$oid || !$user_id){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($user_id != $uid){
            return json(['code'=>-5,'message'=>'用户ID和sessionID不一致！']);
        }
        $info=Db::name('order')->where('order_id',$oid)->find();
        if(!$info){
            return json(['code'=>-2,'message'=>'没有找到该订单！']);
        }
        if($info['user_id']!=$user_id){
            return json(['code'=>-3,'message'=>'你确认的订单和你的身份不匹配！']);
        }
        $score=$info['product_totalprice'];
        // 启动事务
        Db::startTrans();
        try {
            $res=Db::name('order')->where('order_id',$oid)->update(['order_state'=>1]);
            $res=Db::name('user')->where('user_id',$user_id)->setInc('user_integral',$score);
            // 提交事务
            Db::commit();
            return json(['code'=>1,'message'=>'确认收货成功！']);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code'=>-4,'message'=>'确认收货失败！']);
        }
    }
    //用户下单
    public function orderInit(){
        return json(['code'=>-100,'message'=>'该接口已经被启用了']);exit;
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $user_id=input('user_id/d');
        $product_id=input('product_id/d');
        $buy_num=input('buy_num/d');
        $address_id=input('address_id/d');
        $uid=Session::get('user_id','api');
        if(!$product_id || !$user_id || !$buy_num || !$address_id){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($user_id != $uid){
            return json(['code'=>-5,'message'=>'用户ID和sessionID不一致！']);
        }
        $pinfo=Db::name('productList')->where('id',$product_id)->find();
        if(!$pinfo){
            return json(['code'=>-2,'message'=>'该产品已经不存在了！']);
        }
        if($pinfo['product_num']<$buy_num){
            return json(['code'=>-7,'message'=>'产品数量不够！']);
        }
        $ainfo=Db::name('address')->where('id',$address_id)->find();
        if(!$ainfo){
            return json(['code'=>-3,'message'=>'无效的地址ID！']);
        }
        if($ainfo['user_id'] !=$user_id){
            return json(['code'=>-4,'message'=>'异常错误，检查到你的地址ID和你的用户ID不匹配！']);
        }
        $sum=$pinfo['product_price']*intval($buy_num);
        $time=time();
        $data=[
            'user_id'=>$user_id,
            'product_id'=>$product_id,
            'buy_num'=>intval($buy_num),
            'product_totalprice'=>$sum,
            'order_time'=>$time,
            'address_id'=>$address_id
        ];

        // 启动事务
        Db::startTrans();
        try {
            $res=Db::name('order')->insert($data);
            $res=Db::name('productList')->where('id',$product_id)->setDec('product_num',$buy_num);//自减buy_num个;
            // 提交事务
            Db::commit();
            return json(['code'=>1,'message'=>'订单生成成功！']);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code'=>-6,'message'=>'操作失败！']);
        }
    }



}