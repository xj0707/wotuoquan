<?php
namespace app\api\controller;

use think\Db;
use think\Session;

class Product extends CheckSession{
    //产品详情
    public function pinfo(){
//        if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }
        $pid=input('pid/d');
        if(!$pid){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        $info=Db::name('productList')->where('id',$pid)->find();
        if(!$info){
            return json(['code'=>-2,'message'=>'没有找到该产品！']);
        }
        $item_no=$info['item_no'];
        $item=Db::connect('db_config_sql_server')->query("select top (1) * from t_im_branch_stock where item_no='$item_no'");
        $num=0;
        if($item){
            $num=$item[0]['stock_qty'];
        }
        if($info['product_num']!=$num){
            Db::name('productList')->where('id',$pid)->update(['product_num'=>$num]);
        }
        $data=[
            'id'=>$info['id'],
            'p_name'=>$info['product_name'],
            'p_url'=>explode(';',$info['product_url']),
            'p_describe'=>$info['product_describe'],
            'p_price'=>$info['product_price'],
            'p_label'=>$info['product_label'],
            'p_norms'=>$info['product_norms'],
            'p_state'=>$num>0?'现货':'缺货',
            'p_num'=>$num,
            'contact'=>$info['contact'],
            'p_time'=>3//预估时间默认1天
        ];
        return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
    }
    //关键词模糊查询产品
    public function plike(){
        // if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }
        $user_id=input('user_id/d');
        $pname=input('pname');
        if(!$user_id || !$pname){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
//        $uid=Session::get('user_id','api');
//        if($uid != $user_id){
//            return json(['code'=>-2,'message'=>'发生异常错误，用户ID不匹配']);
//        }

        $listinfos=Db::name('productList')->field('id,product_url,product_describe,product_label,product_norms,product_price,contact')->where('product_name','like',"%$pname%")->where('p_state',1)->select();
        if(count($listinfos)){
            $data=[];
            foreach($listinfos as $key=>$listinfo){
                $data[$key]['id']=$listinfo['id'];
                $data[$key]['product_url']=explode(';',$listinfo['product_url']);
                $data[$key]['product_describe']=$listinfo['product_describe'];
                $data[$key]['product_label']=$listinfo['product_label'];
                $data[$key]['product_norms']=$listinfo['product_norms'];
                $data[$key]['product_price']=$listinfo['product_price'];
                $data[$key]['contact']=$listinfo['contact'];
            }
            return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-3,'message'=>'暂无相关产品']);
        }

    }


}