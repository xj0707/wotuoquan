<?php
namespace app\admin\controller;

use think\Db;

class Order extends AdminCommon{

//    public function index(){
//
//        $join=[
//            ['wtq_user u','o.user_id=u.user_id'],
//            ['wtq_address a','o.address_id=a.id']
//        ];
//        $orders=Db::name('order')->alias('o')->join($join)
//             ->field('u.user_phone as uup,o.buy_num,o.product_totalprice,a.user_name,a.user_phone as aup,a.user_address,o.order_time,o.order_state,o.product_id')
//            ->where('a.address_type',1)->paginate(20);
//        $this->assign('orders',$orders);
//
//        return $this->fetch();
//    }


    public function index(){
        $parentid=input('id');
        $join=[
            ['wtq_user u','o.user_id=u.user_id'],
            ['wtq_product_list p','o.product_id=p.id'],
            ['wtq_address a','o.address_id=a.id']
        ];
        if($parentid){
            $orders=Db::name('order')->alias('o')->join($join)
                ->field('o.order_id,u.user_phone as uup,p.product_name,p.product_norms,o.buy_num,o.product_totalprice,a.user_name,a.user_phone as aup,a.user_address,o.order_time,o.order_state,o.pay_state,u.user_type,p.item_no')
                ->where('p.product_parentid',$parentid)->order('o.order_id desc')->paginate(20);
        }else{
            $orders=Db::name('order')->alias('o')->join($join)
                ->field('o.order_id,u.user_phone as uup,p.product_name,p.product_norms,o.buy_num,o.product_totalprice,a.user_name,a.user_phone as aup,a.user_address,o.order_time,o.order_state,o.pay_state,u.user_type,p.item_no')
                ->order('o.order_id desc')->paginate(20);
        }
        $infos = Db::name('product')->where('parent_id', 0)->select();
        $data = array();
        foreach ($infos as $key => $info) {
            $pinfos = Db::name('product')->where('parent_id', $info['id'])->select();
            if ($pinfos) {
                foreach ($pinfos as $k => $pinfo) {
                    $data[$pinfo['id']]['describe'] = $info['describe'] . '---' . $pinfo['describe'];
                }
            } else {
                $data[$info['id']]['describe'] = $info['describe'];
            }
        }
        $new=Db::name('order')->where('order_state',0)->count();
        $complete=Db::name('order')->where('order_state',1)->count();
        $ok=Db::name('order')->where('order_state',2)->count();
        $total=Db::name('order')->count();
        $this->assign('new', $new);
        $this->assign('complete', $complete);
        $this->assign('ok', $ok);
        $this->assign('total', $total);
        $this->assign('data', $data);
        $this->assign('orders',$orders);
        return $this->fetch('index');
    }
    //产品名称查询
    public function searchpname(){
        $product_name=input('product_name');
        $pinfos=Db::name("productList")->field('id')->where('product_name','like',"%$product_name%")->where('p_state',1)->select();
        if(count($pinfos)){
            foreach($pinfos as $pinfo){
                $oinfos=Db::name('order')->field('order_id')->where('product_id',$pinfo['id'])->select();
                foreach ($oinfos as $oinfo) {
                    $ids[]=$oinfo['order_id'];
                }
            }
            $join=[
                ['wtq_user u','o.user_id=u.user_id'],
                ['wtq_product_list p','o.product_id=p.id'],
                ['wtq_address a','o.address_id=a.id']
            ];
            $orders=Db::name('order')->alias('o')->join($join)
                ->field('o.order_id,u.user_phone as uup,p.product_name,p.product_norms,o.buy_num,o.product_totalprice,a.user_name,a.user_phone as aup,a.user_address,o.order_time,o.order_state,o.pay_state,u.user_type,p.item_no')
                ->where('o.order_id','in',$ids)->order('o.order_id desc')->paginate(20);
            $infos = Db::name('product')->where('parent_id', 0)->select();

            foreach ($infos as $key => $info) {
                $pinfos = Db::name('product')->where('parent_id', $info['id'])->select();
                if ($pinfos) {
                    foreach ($pinfos as $k => $pinfo) {
                        $data[$pinfo['id']]['describe'] = $info['describe'] . '---' . $pinfo['describe'];
                    }
                } else {
                    $data[$info['id']]['describe'] = $info['describe'];
                }
            }
        }else{
            $this->error('没有找到该产品','order/index','',1);
        }
        $new=Db::name('order')->where('order_state',0)->count();
        $complete=Db::name('order')->where('order_state',1)->count();
        $ok=Db::name('order')->where('order_state',2)->count();
        $total=Db::name('order')->count();
        $this->assign('new', $new);
        $this->assign('complete', $complete);
        $this->assign('ok', $ok);
        $this->assign('total', $total);
        $this->assign('data', $data);
        $this->assign('orders',$orders);
        return $this->fetch('index');
    }

    //用户电话号码查询
    public function searchphone(){
        $phone=input('user_phone');
        $userinfos=Db::name('user')->field('user_id')->where('user_phone','like',"%$phone%")->select();
        if(count($userinfos)){
            foreach($userinfos as $userinfo){
                $oinfos=Db::name('order')->field('order_id')->where('user_id',$userinfo['user_id'])->select();
                foreach($oinfos as $oinfo){
                    $ids[]=$oinfo['order_id'];
                }
            }
            $join=[
                ['wtq_user u','o.user_id=u.user_id'],
                ['wtq_product_list p','o.product_id=p.id'],
                ['wtq_address a','o.address_id=a.id']
            ];
            $orders=Db::name('order')->alias('o')->join($join)
                ->field('o.order_id,u.user_phone as uup,p.product_name,p.product_norms,o.buy_num,o.product_totalprice,a.user_name,a.user_phone as aup,a.user_address,o.order_time,o.order_state,o.pay_state,u.user_type,p.item_no')
                ->where('o.order_id','in',$ids)->order('o.order_id desc')->paginate(20);
            $infos = Db::name('product')->where('parent_id', 0)->select();
            $data = array();
            foreach ($infos as $key => $info) {
                $pinfos = Db::name('product')->where('parent_id', $info['id'])->select();
                if ($pinfos) {
                    foreach ($pinfos as $k => $pinfo) {
                        $data[$pinfo['id']]['describe'] = $info['describe'] . '---' . $pinfo['describe'];
                    }
                } else {
                    $data[$info['id']]['describe'] = $info['describe'];
                }
            }
        }else{
            $this->error('未找到该电话的订单！','order/index','',1);
        }
        $new=Db::name('order')->where('order_state',0)->count();
        $complete=Db::name('order')->where('order_state',1)->count();
        $ok=Db::name('order')->where('order_state',2)->count();
        $total=Db::name('order')->count();
        $this->assign('new', $new);
        $this->assign('complete', $complete);
        $this->assign('ok', $ok);
        $this->assign('total', $total);
        $this->assign('data', $data);
        $this->assign('orders',$orders);
        return $this->fetch('index');
    }
    //订单处理
    public function handle(){
        $oid=input('oid');
        if($oid){
            $info=Db::name('order')->where('order_id',$oid)->update(['order_state'=>2]);
            if($info){
                $this->success('操作成功','order/index','',1);
            }else{
                $this->success('操作失败','order/index','',1);
            }
        }
    }















}