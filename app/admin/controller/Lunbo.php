<?php
namespace app\admin\controller;

use think\Db;
use think\Request;

class Lunbo extends AdminCommon{
    //首页轮播
    public function homeindex(){
        $info=Db::name('homeLunbo')->where('lunbo_type',1)->paginate(20);
        $this->assign('info',$info);
        $this->assign('type',1);
        return $this->fetch();
    }
    //添加轮播
    public function addlunbo(){
        $typeid=input('id');
        $this->assign('typeid',$typeid);
        return $this->fetch();
    }
    //添加轮播验证
    public function addform(Request $request){
       $info=input();
        // 获取表单上传文件
        $file = $request->file('file');
        // 上传文件验证
        $result = $this->validate(['file' => $file], ['file' => 'require|image'], ['file.require' => '请选择上传文件', 'file.image' => '非法图像文件']);

        if (true !== $result) {
            $this->error($result);
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $fileinfo = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($fileinfo){
            $filename=$fileinfo->getSaveName();
            $filename=str_replace('\\','/',$filename);
            $data=[
                'lunbo_describe'=>$info['lunbo_describe'],
                'product_url'=>$filename,
                'lunbo_type'=>$info['typeid']
            ];
            $res=Db::name('homeLunbo')->insert($data);
            if ($res) {
                $url=$info['typeid']==1?'homeindex':'hotindex';
                return $this->success('添加成功', 'lunbo/'.$url);
            } else {
                return $this->error('添加失败');
            }
        }
    }
    //删除
    public function doDel(){
        $id=input('id');
        $info=Db::name("homeLunbo")->where('id',$id)->find();
        $filename=$info['product_url'];
        $res=Db::name("homeLunbo")->delete($id);
        if ($res) {
            $url=ROOT_PATH . 'public' . DS . 'uploads/'.$filename;
            if(file_exists($url)){
                unlink($url);
            }
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }
    //修改
    public function ledit(){
        $id=input('id');
        $info=Db::name('homeLunbo')->where('id',$id)->find();
        $this->assign('info',$info);
        $this->assign('id',$id);
        return $this->fetch();
    }
    //修改表单验证
    public function editform(Request $request){
        $info=input();
        // 获取表单上传文件
        $file = $request->file('file');
        if($file){
            // 上传文件验证
            $result = $this->validate(['file' => $file], ['file' => 'require|image'], ['file.require' => '请选择上传文件', 'file.image' => '非法图像文件']);
            if (true !== $result) {
                $this->error($result);
            }
            // 移动到框架应用根目录/public/uploads/ 目录下
            $fileinfo = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($fileinfo){
                $plinfo=Db::name('homeLunbo')->field('product_url')->where('id', $info['id'])->find();
                $filename=$plinfo['product_url'];
                $url=ROOT_PATH . 'public' . DS . 'uploads/'.$filename;
                if(file_exists($url)){
                    unlink($url);
                }
                $filename=$fileinfo->getSaveName();
                $filename=str_replace('\\','/',$filename);
                $data['product_url']=$filename;
            }else{
                $this->error('图片上传失败');
            }
        }
        $data['lunbo_describe']=$info['lunbo_describe'];
        $res=Db::name('homeLunbo')->where('id',$info['id'])->update($data);
        if ($res) {
            $homelunbo=Db::name('homeLunbo')->where('id',$info['id'])->find();
            $url=$homelunbo['lunbo_type']==1?'homeindex':'hotindex';
            return $this->success('更新成功', 'lunbo/'.$url);
        } else {
            return $this->error('更新失败');
        }
    }
    //点击应用
    public function lactive(){
        $id=input('id');
        $res=Db::name('homeLunbo')->where('id',$id)->update(['product_state'=>1]);
        if ($res) {
            $homelunbo=Db::name('homeLunbo')->where('id',$id)->find();
            $url=$homelunbo['lunbo_type']==1?'homeindex':'hotindex';
            return $this->success('应用成功', 'lunbo/'.$url);
        } else {
            return $this->error('应用失败');
        }
    }
    //解除应用
    public function unactive(){
        $id=input('id');
        $res=Db::name('homeLunbo')->where('id',$id)->update(['product_state'=>0]);
        if ($res) {
            $homelunbo=Db::name('homeLunbo')->where('id',$id)->find();
            $url=$homelunbo['lunbo_type']==1?'homeindex':'hotindex';
            return $this->success('解除应用成功', 'lunbo/'.$url);
        } else {
            return $this->error('操作失败');
        }
    }
    //推荐模块轮播
    public function hotindex(){
        $info=Db::name('homeLunbo')->where('lunbo_type',2)->paginate(20);
        $this->assign('info',$info);
        $this->assign('type',2);
        return $this->fetch('homeindex');
    }
}