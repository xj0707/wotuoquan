<?php
namespace app\admin\controller;

use think\Db;
use think\Loader;


/**
* 管理员管理
* @author aierui github  https://github.com/Aierui
* @version 1.0 
*/
class Admin extends AdminCommon
{

    function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 列表
     */
    public function index()
    {
        $lists=Db::name('admin')->where('admin_type',1)->select();
        $this->assign('lists',$lists);
       return $this->fetch();
    }


    /**
     * 添加
     */
    public function add()
    {
        $userinfo=session('admin.userinfo');
        if($userinfo['admin_level']!=1){
            return $this->error('你没有该权限！');
        }
        return $this->fetch('add');
    }


    /**
     * 编辑
     * @param  string $id 数据ID（主键）
     */
    public function edit($id = 0)
    {
        $userinfo=session('admin.userinfo');
        if($userinfo['admin_level']!=1){
            return $this->error('你没有该权限！');
        }
        $id=input('id');
        $admininfo=Db::name('admin')->where('id',$id)->find();
        $this->assign('admininfo',$admininfo);
        return $this->fetch();
    }

    /**
     * 删除
     * @param  string $id 数据ID（主键）
     */
    public function doDel($id = 0){
        $userinfo=session('admin.userinfo');
        if($userinfo['admin_level']!=1){
            return $this->error('你没有该权限！');
        }
        $info=input();
        $res=Db::name('admin')->where('id',$info['id'])->update(['admin_type'=>0]);//软删除
        if($res){
            $this->success('删除成功','admin/index');
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 添加表单验证
     */
   public  function doAdd(){
       $info=input('post.');
       $result = $this->validate($info,'Admin');
       if (true !== $result) {
           return $this->error($result);
       }
       $res=Db::name('admin')->where('admin_user',$info['admin_user'])->find();
       if($res){
           return $this->error('该用户已经存在了');
       }
       $time=time();
       $data=[
           'admin_user'=>$info['admin_user'],
           'admin_pwd'=>$info['admin_pwd'],
           'admin_type'=>1,
           'admin_level'=>2,//默认给个普通管理员2
           'create_time'=>$time,
       ];
       $int=Db::name('admin')->insert($data);
       if($int){
           $this->success('添加成功','admin/index');
       }else{
           $this->error('添加失败');
       }
   }
    /**
     * 修改表单验证
     */
    public function doEdit(){
        $info=input('post.');
        $data=array();
        if($info['new_pwd']){
            if(strlen($info['new_pwd'])>16 ||strlen($info['new_pwd'])<6) {
                return $this->error('密码应该在6到16位');
            }
//            if($info['old_pwd']){
//                if(md5($info['old_pwd'])!=$userinfo['admin_pwd']){
//                    return $this->error('你的原始密码不正确');
//                }
//            }else{
//                return $this->error('更新密码必须输入原始密码');
//            }
            $data['admin_pwd']=$info['new_pwd'];
        }
        $data['admin_user']=$info['admin_user'];
        $res=Db::name('admin')
            ->where('id', $info['admin_id'])
            ->update($data);
        if($res){
            $this->success('更新成功','admin/index');
        }else{
            $this->error('更新失败');
        }
    }






}