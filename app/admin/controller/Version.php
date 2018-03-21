<?php
namespace  app\admin\controller;

use think\Db;

class Version extends AdminCommon{
    public function index(){
        $lists=Db::name('version')->select();
        $this->assign('lists',$lists);
        return $this->fetch();
    }
    //添加版本信息
    public function addversion(){
        return $this->fetch();
    }
    //表单验证
    public function addform(){
       $v_number=$this->checkParam(input('v_number'));
       $v_info=$this->checkParam(input('v_info'));
       if(!$v_number || !$v_info){
           $this->error('请填写完整的信息');
       }
       $int=Db::name('version')->insert(['v_number'=>$v_number,'v_info'=>$v_info]);
        if($int){
            $this->success('操作成功','version/index');
        }else{
            $this->error('添加失败');
        }
    }
    //删除
    public function doDel(){
        $id=input('id');
        $int=Db::name('version')->delete($id);
        if($int){
            $this->success('操作成功','version/index');
        }else{
            $this->error('操作失败');
        }
    }

}