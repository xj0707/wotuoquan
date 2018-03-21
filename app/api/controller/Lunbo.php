<?php
namespace app\api\controller;

use think\Db;
use think\Session;

/**
 * 首页模块
 * @package app\api\controller\v1
 */
class Lunbo extends CheckSession{
    //获得首页轮播图
    public function getHomeLunbo(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $info=Db::name('homeLunbo')->field('id,product_url')->where('product_state',1)->where('lunbo_type',1)->limit(5)->select();
        return json(['data'=>$info,'code'=>1,'message'=>'操作成功！']);
    }
    //获得水产品
    public function getWater(){
//        if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }
        $flag=input('flag');
        if(!$flag){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($flag==='W'){
            $pinfo=Db::name('product')->where('product_name',$flag)->find();
            if(!$pinfo){
                return json(['code'=>-3,'message'=>'没有找到该产品啊！']);
            }
            $id=$pinfo['id'];
            $listinfos=Db::name('productList')->field('id,product_url,product_describe,product_label,product_norms,product_price,contact')->where('product_parentid',$id)->select();

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
                return json(['code'=>-4,'message'=>'没有找到该产品！']);
            }
        }else{
            return json(['code'=>-2,'message'=>'与约定行标志不一样！']);
        }
    }
    //获得引用器皿的类
    public function getDtype(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $flag=input('flag');
        if(!$flag){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($flag==='D'){
            $pinfo=Db::name('product')->where('product_name',$flag)->find();
            if(!$pinfo){
                return json(['code'=>-3,'message'=>'没有找到该产品啊！']);
            }
            $id=$pinfo['id'];
            $listinfos=Db::name('product')->field('id,product_name,describe,product_url')->where('parent_id',$id)->select();
            return json(['data'=>$listinfos,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-2,'message'=>'与约定行标志不一样！']);
        }
    }
    //获得农产品的类
    public function getFtype(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $flag=input('flag');
        if(!$flag){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($flag==='F'){
            $pinfo=Db::name('product')->where('product_name',$flag)->find();
            if(!$pinfo){
                return json(['code'=>-3,'message'=>'没有找到该产品啊！']);
            }
            $id=$pinfo['id'];
            $listinfos=Db::name('product')->field('id,product_name,describe,product_url')->where('parent_id',$id)->select();
            return json(['data'=>$listinfos,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-2,'message'=>'与约定行标志不一样！']);
        }
    }
    //获得类下面的产品
    public function getProduct(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $t_id=input('type_id/d');
        if(!$t_id){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        $info=Db::name('product')->where('id',$t_id)->find();
        if(!$info){
            return json(['code'=>-2,'message'=>'发生异常，没有该类！']);
        }
        $listinfos=Db::name('productList')->field('id,product_url,product_describe,product_label,product_norms,product_price,contact')->where('product_parentid',$t_id)->select();
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
            return json(['code'=>-3,'message'=>'没有该产品！']);
        }


    }
    //获得推荐轮播图
    public function getHotLunbo(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $info=Db::name('homeLunbo')->field('id,product_url')->where('product_state',1)->where('lunbo_type',2)->limit(5)->select();
        return json(['data'=>$info,'code'=>1,'message'=>'操作成功！']);
    }
    //获得推荐水
    public function getHotWater(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $info=Db::name('product')->where('product_name',"W")->where('parent_id',0)->find();
        if(!$info){
            return json(['code'=>-1,'message'=>'发生异常的错误！']);
        }
        $listinfos=Db::name('productList')->field('id,product_url,product_describe,product_label,product_norms,product_price,s_describe,contact')->where('product_parentid',$info['id'])->where('is_suggest',1)->select();
        if(count($listinfos)){
            $data=[];
            foreach($listinfos as $key=>$listinfo){
                $data[$key]['id']=$listinfo['id'];
                $data[$key]['product_url']=explode(';',$listinfo['product_url']);
                $data[$key]['product_describe']=$listinfo['product_describe'];
                $data[$key]['product_label']=$listinfo['product_label'];
                $data[$key]['product_norms']=$listinfo['product_norms'];
                $data[$key]['product_price']=$listinfo['product_price'];
                $data[$key]['product_liyou']=$listinfo['s_describe'];
                $data[$key]['contact']=$listinfo['contact'];
            }
            return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-2,'message'=>'没有找到推荐产品！']);
        }

    }
    //获得其他推荐
    public function getHotOther(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $info=Db::name('product')->where('product_name',"W")->where('parent_id',0)->find();
        if(!$info){
            return json(['code'=>-1,'message'=>'发生异常的错误！']);
        }
        $hotinfos=Db::name('productList')->field('id,product_parentid,product_url,product_describe,product_label,product_norms,product_price,s_describe,contact')->where('is_suggest',1)->where('product_parentid','<>',$info['id'])->select();
        if(count($hotinfos)){
            $data=[];
            foreach ($hotinfos as $key=>$hotinfo) {
                $data[$key]['id']=$hotinfo['id'];
                $data[$key]['product_url']=explode(';',$hotinfo['product_url']);
                $data[$key]['product_describe']=$hotinfo['product_describe'];
                $data[$key]['product_label']=$hotinfo['product_label'];
                $data[$key]['product_norms']=$hotinfo['product_norms'];
                $data[$key]['product_price']=$hotinfo['product_price'];
                $data[$key]['product_liyou']=$hotinfo['s_describe'];
                $data[$key]['contact']=$hotinfo['contact'];
            }
            return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-2,'message'=>'无其他推荐！']);
        }

    }

}