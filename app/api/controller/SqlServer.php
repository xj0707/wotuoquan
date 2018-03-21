<?php
namespace app\api\controller;

use think\Db;
use think\Session;

/**
 * 接口连接sql_server服务器
 */
class SqlServer extends CheckSession{
    //获得窝驼泉产品
    public function getWt(){
        //        if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }
        $flag=input('flag');
        if(!$flag){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($flag==='W'){
            $listinfos=Db::connect('db_config_sql_server')->query("select top (100) * from t_bd_item_info where item_clsno='01'");
            //$listinfos=Db::name('productList')->field('id,product_url,product_describe,product_label,product_norms,product_price,contact')->where('product_parentid',$id)->select();
            if(count($listinfos)){
                $data=[];
                foreach($listinfos as $key=>$listinfo){
                    $data[$key]['id']=$listinfo['item_no'];
                    $data[$key]['product_url']=$listinfo['item_picture'];
                    $data[$key]['product_describe']=$listinfo['item_name'];
                    //$data[$key]['product_label']=$listinfo['pro_code1'];
                    $data[$key]['product_norms']=$listinfo['item_size'].'/'.$listinfo['unit_no'];
                    $data[$key]['product_price']=$listinfo['sale_price'];
                    $main_supcust=$listinfo['main_supcust'];
                    $maininfo=Db::connect('db_config_sql_server')->query("select top (1) * from t_bd_supcust_info where supcust_no='$main_supcust'");
                    $data[$key]['contact']=$maininfo[0]['sup_tel'];
                }
                return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
            }else{
                return json(['code'=>-4,'message'=>'没有找到该产品！']);
            }
        }else{
            return json(['code'=>-2,'message'=>'与约定行标志不一样！']);
        }
    }

    //获得除了水的其他类别
    public function getOtype(){
//        if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }
        $flag=input('flag');
        if(!$flag){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($flag==='O'){
            $listinfos=Db::connect('db_config_sql_server')->query("select top (100) * from t_bd_item_cls where item_clsno!='01'");
            $data=[];
            foreach ($listinfos as $key=>$listinfo){
                $data[$key]['id']=$listinfo['item_clsno'];
                $data[$key]['product_name']=$listinfo['item_clsname'];
            }
            return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-2,'message'=>'与约定行标志不一样！']);
        }
    }
    //获得推荐水
    public function getHotWater(){
//        if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }
        $listinfos=Db::connect('db_config_sql_server')->query("select top (100) * from t_bd_item_info where item_clsno='01' and pro_code2='1'");
        if(count($listinfos)){
            $data=[];
            foreach($listinfos as $key=>$listinfo){
                $data[$key]['id']=$listinfo['item_no'];
                $data[$key]['product_url']=$listinfo['item_picture'];
                $data[$key]['product_describe']=$listinfo['item_name'];
                //$data[$key]['product_label']=$listinfo['pro_code1'];
                $data[$key]['product_norms']=$listinfo['item_size'].'/'.$listinfo['unit_no'];
                $data[$key]['product_price']=$listinfo['sale_price'];
                //$data[$key]['product_liyou']=$listinfo['pro_code2'];
                $main_supcust=$listinfo['main_supcust'];
                $maininfo=Db::connect('db_config_sql_server')->query("select top (1) * from t_bd_supcust_info where supcust_no='$main_supcust'");
                $data[$key]['contact']=$maininfo[0]['sup_tel'];
            }
            return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-2,'message'=>'没有找到推荐产品！']);
        }

    }

    //获得其他推荐
    public function getHotOther(){
//        if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }

        $hotinfos=Db::connect('db_config_sql_server')->query("select top (100) * from t_bd_item_info where item_clsno !='01' and pro_code2='1' ");
        if(count($hotinfos)){
            $data=[];
            foreach ($hotinfos as $key=>$hotinfo) {
                $data[$key]['id']=$hotinfo['item_no'];
                $data[$key]['product_url']=$hotinfo['item_picture'];
                $data[$key]['product_describe']=$hotinfo['item_name'];
                //$data[$key]['product_label']=$hotinfo['pro_code1'];
                $data[$key]['product_norms']=$hotinfo['item_size'].'/'.$hotinfo['unit_no'];
                $data[$key]['product_price']=$hotinfo['sale_price'];
                //$data[$key]['product_liyou']=$hotinfo['pro_code3'];
                $main_supcust=$hotinfo['main_supcust'];
                $maininfo=Db::connect('db_config_sql_server')->query("select top (1) * from t_bd_supcust_info where supcust_no='$main_supcust'");
                $data[$key]['contact']=$maininfo[0]['sup_tel'];
            }
            return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-2,'message'=>'无其他推荐！']);
        }

    }

    //产品详情
    public function pinfo(){
//        if(!Session::has('user_id','api')){
//            return json(['code'=>-100,'message'=>'你还没有登录！']);
//        }
        $pid=input('pid');
        if(!$pid){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        $info=Db::connect('db_config_sql_server')->query("select top (1) * from t_bd_item_info where item_no ='$pid' ");
        if(!$info){
            return json(['code'=>-2,'message'=>'没有找到该产品！']);
        }
        $main_supcust=$info[0]['main_supcust'];
        $maininfo=Db::connect('db_config_sql_server')->query("select top (1) * from t_bd_supcust_info where supcust_no='$main_supcust'");
        $sup_tel=$maininfo[0]['sup_tel'];
        $item=Db::connect('db_config_sql_server')->query("select top (1) * from t_im_branch_stock where item_no='$pid'");
        $num=0;
        if($item){
            $num=$item[0]['stock_qty'];
        }
        $data=[
            'id'=>$info[0]['item_no'],
            'p_name'=>$info[0]['item_name'],
            'p_url'=>$info[0]['item_picture'],
            'p_describe'=>$info[0]['memo'],
            'p_price'=>$info[0]['sale_price'],
            //'p_label'=>$info['pro_code1'],
            'p_norms'=>$info[0]['item_size'].'/'.$info[0]['unit_no'],

            'p_state'=>$num>0?'现货':'缺货',
            'contact'=>$sup_tel,
            'p_time'=>1//预估时间默认1天
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
        $listinfos=Db::connect('db_config_sql_server')->query("select top (100) * from t_bd_item_info where item_name LIKE '%$pname%' ");
        if(count($listinfos)){
            $data=[];
            foreach($listinfos as $key=>$listinfo){
                $data[$key]['id']=$listinfo['item_no'];
                $data[$key]['product_url']=$listinfo['item_picture'];
                $data[$key]['product_describe']=$listinfo['item_name'];
                //$data[$key]['product_label']=$hotinfo['pro_code1'];
                $data[$key]['product_norms']=$listinfo['item_size'].'/'.$listinfo['unit_no'];
                $data[$key]['product_price']=$listinfo['sale_price'];
                //$data[$key]['product_liyou']=$hotinfo['pro_code3'];
                $main_supcust=$listinfo['main_supcust'];
                $maininfo=Db::connect('db_config_sql_server')->query("select top (1) * from t_bd_supcust_info where supcust_no='$main_supcust'");
                $data[$key]['contact']=$maininfo[0]['sup_tel'];
            }
            return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-3,'message'=>'暂无相关产品']);
        }

    }

    //用户下单
    public function orderInit(){
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
        $pinfo=Db::connect('db_config_sql_server')->query("select top (1) * from t_bd_item_info where item_no ='$product_id' ");
        if(!$pinfo){
            return json(['code'=>-2,'message'=>'该产品已经不存在了！']);
        }
        $item=Db::connect('db_config_sql_server')->query("select top (1) * from t_im_branch_stock where item_no='$product_id'");
        $num=0;
        if($item){
            $num=$item[0]['stock_qty'];
        }
        if($num<$buy_num){
            return json(['code'=>-7,'message'=>'产品数量不够！']);
        }
        $ainfo=Db::name('address')->where('id',$address_id)->find();
        if(!$ainfo){
            return json(['code'=>-3,'message'=>'无效的地址ID！']);
        }
        if($ainfo['user_id'] !=$user_id){
            return json(['code'=>-4,'message'=>'异常错误，检查到你的地址ID和你的用户ID不匹配！']);
        }
        $sum=$pinfo[0]['sale_price']*intval($buy_num);
        $time=time();
        $data=[
            'user_id'=>$user_id,
            'product_id'=>$product_id,
            'buy_num'=>intval($buy_num),
            'product_totalprice'=>$sum,
            'order_time'=>$time,
            'address_id'=>$address_id
        ];
        $res=Db::name('order')->insert($data);
        if($res){
            return json(['code'=>1,'message'=>'订单生成成功！']);
        }else{
            return json(['code'=>-6,'message'=>'操作失败！']);
        }

    }
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
                $product_id=$pid['product_id'];
                $pinfo=Db::connect('db_config_sql_server')->query("select top (1) * from t_bd_item_info where item_no ='$product_id' ");
                if($pinfo){
                    $data[$key]['product_name']=$pinfo[0]['item_name'];
                    $data[$key]['product_describe']=$pinfo[0]['memo'];
                }
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




}