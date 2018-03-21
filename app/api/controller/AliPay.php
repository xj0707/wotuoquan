<?php
namespace app\api\controller;

class AliPay extends CheckSession{
    //订单支付
    public  function payOrder(){
        //这里接受订单 查询整理结果返回支付数据。
        $alipay= new \app\common\controller\AliPay();
        //参数自己看上面AliPay.php的tradeAppPay函数
        $orderBody='窝托泉';
        $orderTitle='商品名称';
        $out_trade_no='唯一订单号';
        $price='支付的总价格';
        $orderInfo    = $alipay->tradeAppPay($orderBody, $orderTitle, $out_trade_no, (float)$price);
        //将获取的参数返回给app端 jsonReturn是自定义的方法
        return json(['data'=>$orderInfo,'code'=>1,'message'=>'操作成功！']);
    }


    //支付宝支付异步通知
    public function AliPayNotify()
    {
        $request    = input('post.');

        //写入文件做日志 调试用
//        $log = "<br />\r\n\r\n".'==================='."\r\n".date("Y-m-d H:i:s")."\r\n".json_encode($request);
//        @file_put_contents('upload/alipay.html', $log, FILE_APPEND);

        $signType = $request['sign_type'];
        $alipay = new \app\common\controller\AliPay();
        $flag = $alipay->rsaCheck($request, $signType);

        if ($flag) {
            //支付成功:TRADE_SUCCESS   交易完成：TRADE_FINISHED
            if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED') {
                //这里根据项目需求来写你的操作 如更新订单状态等信息 更新成功返回'success'即可
//                $object =  json_decode(($request['fund_bill_list']),true);
//                $trade_type     =   $object[0]['fundChannel'];
//                $data    =    [
//                    'pay_status'        =>   1,
//                    'pay_type'             =>   1,
//                    'trade_type'        =>   $trade_type,
//                    'pay_time'          =>   strtotime($request['gmt_payment'])
//                ];
//                $buyer_pay_amount = $request['buyer_pay_amount'];
//                $out_trade_no   =   $request['out_trade_no'];
                $saveorder      =   model('orders')->successPay($out_trade_no,$buyer_pay_amount,$data);
                if ($saveorder==1) {
                    exit('success'); //成功处理后必须输出这个字符串给支付宝,否则会一直回调
                } else {
                    exit('fail');
                }
            } else {
                exit('fail');
            }
        } else {
            exit('fail');
        }
    }



}