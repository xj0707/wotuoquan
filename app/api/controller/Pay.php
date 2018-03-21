<?php
namespace app\api\controller;

use think\Db;

class Pay extends CheckSession{

    //支付宝回调接口
    public function aliurl(){
        $data   = input('post.');
        if ($data['trade_status'] == 'TRADE_SUCCESS' || $data['trade_status'] == 'TRADE_FINISHED') {
            //这里根据项目需求来写你的操作 如更新订单状态等信息 更新成功返回'success'即可
            $total_amount = $data['total_amount'];//支付总价格
            $out_trade_no   =   $data['out_trade_no'];
            $trade_no   =   $data['trade_no'];//支付的编号
            $arr=explode('_',$out_trade_no);
            $p_id=$arr[1];//产品ID
            $p_num=$arr[2];//产品数量
            $user_id=$arr[3];//用户ID
            $address_id=$arr[4];//地址ID
            //这里要生成订单并且生成用户积分(这里处理放在用户确认收货再加积分)
            $orderinfo=Db::name('order')->where('payid',$trade_no)->find();
            if(!$orderinfo){
                $time=time();
                $insertdata=[
                    'user_id'=>$user_id,
                    'product_id'=>$p_id,
                    'buy_num'=>$p_num,
                    'product_totalprice'=>$total_amount,
                    'order_time'=>$time,
                    'address_id'=>$address_id,
                    'payid'=>$trade_no,
                    'pay_state'=>2,
                ];
                $orderid=Db::name('order')->insertGetId($insertdata);
                //记录支付日志
                if($orderid){
                    //更新库存
                    $pinfo=Db::name('productList')->where('id',$p_id)->find();
                    if($pinfo){
                        $item_no=$pinfo['item_no'];
                        $item=Db::connect('db_config_sql_server')->query("select top (1) * from t_im_branch_stock where item_no='$item_no'");
                        if($item){
                            $num=$item[0]['stock_qty'];
                            $truenum=$num-$p_num<=0?0:$num-$p_num;
                        }
                        if($pinfo['product_num']!=$truenum){
                            Db::name('productList')->where('id',$p_id)->update(['product_num'=>$truenum]);
                            Db::connect('db_config_sql_server')->query("update t_im_branch_stock set stock_qty='$truenum' where item_no='$item_no'");
                        }
                    }
                    //file_put_contents('alirecode.txt',$item_no.'=='.$num.'=='.$truenum.'=='.$pinfo['product_num'],FILE_APPEND);
                    $paydata=[
                        'orderid'=>$orderid,
                        'userid'=>$user_id,
                        'payid'=>$trade_no,
                        'create_time'=>$time,
                        'paystate'=>2
                    ];
                    Db::name('payinfo')->insert($paydata);
                }
            }
            exit('success'); //成功处理后必须输出这个字符串给支付宝,否则会一直回调
        } else {
            exit('fail');
        }

    }
    //微信支付回调接口
    public function wxurl(){
        $xml = file_get_contents('php://input');
        //file_put_contents('wxurl.txt',$xml."\n",FILE_APPEND);
        $xmlobj=simplexml_load_string($xml);
        if($xmlobj->result_code=='SUCCESS' && $xmlobj->return_code=='SUCCESS') {
            $jhpcarr = explode('_', $xmlobj->out_trade_no);
            $p_id=$jhpcarr[1];//产品ID
            $p_num=$jhpcarr[2];//产品数量
            $user_id=$jhpcarr[3];//用户ID
            $address_id=$jhpcarr[4];//地址ID
            $price = ($xmlobj->total_fee) / 100; //支付金额
            $tid = $xmlobj->transaction_id; //支付的唯一标识transaction_id
            //这里要生成订单并且生成用户积分(这里处理放在用户确认收货再加积分)
            $orderinfo=Db::name('order')->where('payid',$tid)->find();
            if(!$orderinfo){
                $time=time();
                $insertdata=[
                    'user_id'=>$user_id,
                    'product_id'=>$p_id,
                    'buy_num'=>$p_num,
                    'product_totalprice'=>$price,
                    'order_time'=>$time,
                    'address_id'=>$address_id,
                    'payid'=>(string)$tid,
                    'pay_state'=>1,
                ];
                $orderid=Db::name('order')->insertGetId($insertdata);
                //记录支付日志
                if($orderid){
                    //更新库存
                    echo 1;
                    $pinfo=Db::name('productList')->where('id',$p_id)->find();
                    if($pinfo){
                        $item_no=$pinfo['item_no'];
                        $item=Db::connect('db_config_sql_server')->query("select top (1) * from t_im_branch_stock where item_no='$item_no'");
                        if($item){
                            $num=$item[0]['stock_qty'];
                            $truenum=$num-$p_num<=0?0:$num-$p_num;
                        }
                        if($pinfo['product_num']!=$truenum){
                            Db::name('productList')->where('id',$p_id)->update(['product_num'=>$truenum]);
                            Db::connect('db_config_sql_server')->query("update t_im_branch_stock set stock_qty='$truenum' where item_no='$item_no'");
                        }
                    }
                   // file_put_contents('wxrecode.txt',$item_no.'=='.$num.'=='.$truenum.'=='.$pinfo['product_num'],FILE_APPEND);
                    $paydata=[
                        'orderid'=>$orderid,
                        'userid'=>$user_id,
                        'payid'=>(string)$tid,
                        'create_time'=>$time,
                        'paystate'=>1
                    ];
                    Db::name('payinfo')->insert($paydata);
                    return "<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>";
                }
            }else{
                return "<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>";
            }

        }

    }
    //获取派向分系统的id
    public function  getAdminId($address_id){

        $info=Db::name('address')->where('id',$address_id)->find();
        if(!$info){
            file_put_contents('a.txt','地址id未找到',FILE_APPEND);
        }
        $alng=$info['lng'];
        $alat=$info['lat'];
        $admininfos=Db::name('admin')->where('admin_type',1)->where('admin_level',3)->select();
        $arr=[];
        foreach ($admininfos as $admininfo){
            $id=$admininfo['id'];
            $blng=$admininfo['lng'];
            $blat=$admininfo['lat'];
            $juli=$this->get_distance([$alng,$alat],[$blng,$blat]);
            $arr[$id]=$juli;
        }
        asort($arr);
        $adminId=current(array_keys($arr));
        return $adminId;
    }

    public function get_distance($from,$to,$km=true,$decimal=5){
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $distance = $EARTH_RADIUS*2*asin(sqrt(pow(sin( ($from[0]*pi()/180-$to[0]*pi()/180)/2),2)+cos($from[0]*pi()/180)*cos($to[0]*pi()/180)* pow(sin( ($from[1]*pi()/180-$to[1]*pi()/180)/2),2)))*1000;
        if($km){
            $distance = $distance / 1000;
        }
        return round($distance, $decimal);
    }


}
