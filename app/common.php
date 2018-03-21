<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

function paystate($int){
    if ($int == 1) {
        return '微信支付';
    } elseif($int==2) {
        return '支付宝支付';
    }else{
        return '未知';
    }
}

// 应用公共文件
function checktype($id)
{
    if ($id === 1) {
        return '启用';
    } else {
        return '禁用';
    }
}
function checklevel($id){
    if ($id === 1) {
        return '超级管理员';
    } else {
        return '普通管理员';
    }
}
function checkline($id){
    if ($id === 1) {
        return '<span style="color:green;">在线</span>';
    } else {
        return '未上线';
    }
}
function checkparentId(){

}

function img($str){
    $arr=explode(';',$str);
    $leng='';
    foreach($arr as $value){
        $leng.="<img src='__ROOT__/uploads/{$value}' alt=''width='50px' />";
    }
    return $leng;
}
/**
 * 调试输出
 * @param unknown $data
 */
function print_data($data, $var_dump = false)
{
    header("Content-type: text/html; charset=utf-8");
    echo "<pre>";
    if ($var_dump) {
        var_dump($data);
    } else {
        print_r($data);
    }
    exit();
}

/**
 * 输出json格式数据
 * @param unknown $object
 */
function print_json($object)
{
    header("content-type:text/plan;charset=utf-8");
    echo json_encode($object);
    exit();
}

/**
 * 账户密码加密
 * @param  string $str password
 * @return string(32)       
 */
function md6($str)
{
	$key = 'account_nobody';
	return md5(md5($str).$key);
}

/**
 * 替换字符串中间位置字符为星号
 * @param  [type] $str [description]
 * @return [type] [description]
 */
function replaceToStar($str)
{
    $len = strlen($str) / 2; //a0dca4d0****************ba444758]
    return substr_replace($str, str_repeat('*', $len), floor(($len) / 2), $len);
}

function mduser( $str )
{
    $user_auth_key = \think\Config::get('user_auth_key');
    return md5(md5($user_auth_key).$str);
}
