<?php
namespace app\api\controller;

use think\Db;

class Version extends CheckSession{

    //检测是否可以更新
    public function checkVersion(){
        $v_num=trim(input('v_num'));
        if(!$v_num){
            return json(['code'=>-1,'message'=>'参数不完整']);
        }
        $info=Db::name('version')->order('id desc')->find();
        if(!$info){
            return json(['code'=>-2,'message'=>'异常错误，服务器上无版本记录']);
        }
        preg_match('/(\d+.)+/',$v_num,$parr);
        $v_num=str_replace('.','',$parr[0]);
        preg_match('/(\d+.)+/',$info['v_number'],$serparr);
        $vser_num=str_replace('.','',$serparr[0]);
        if(intval($v_num) < intval($vser_num)){
            return json(['data'=>$info,'code'=>1,'message'=>'有新的版本了']);
        }else{
            return json(['code'=>-3,'message'=>'无更新的版本']);
        }
    }

    public function test(){
        $listinfos=Db::connect('db_config_sql_server')->query("select top (100) * from t_bd_item_info where item_clsno !='01' ");
        var_dump($listinfos);
    }


}