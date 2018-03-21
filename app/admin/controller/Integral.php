<?php
namespace app\admin\controller;

use think\Db;
use think\Request;

class Integral extends AdminCommon{
    //所有奖品
    public function index(){
        $lists = Db::name('scoreProduct')->order('id desc')->paginate(20);
        $this->assign('lists', $lists);
        return $this->fetch();
    }

    //添加奖品
    public function addproduct(){
        return $this->fetch();
    }
    //添加奖品验证
    public function addform(Request $request){
        $info=input();
        $result = $this->validate($info, 'ScoreProduct');
        if (true !== $result) {
            return $this->error($result);
        }
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
                'score_product'=>$info['score_product'],
                'score_url'=>$filename,
                'score_describe'=>$info['score_describe'],
                'score_norms'=>$info['score_guige'].$info['score_norms'],
                'consume_score'=>$info['consume_score'],
                'score_num'=>$info['score_num']
            ];
            $res=Db::name('scoreProduct')->insert($data);
            if ($res) {
                return $this->success('添加成功','integral/index');
            } else {
                return $this->error('添加失败');
            }
        }
    }
    //修改奖品
    public function pedit(){
        $id=input('id');
        $info=Db::name('scoreProduct')->where('id',$id)->find();
        $guige=$info['score_norms'];
        $arr=explode('/',$guige);
        if(count($arr)!=2){
            preg_match('/([0-9]+)(.*)/',$guige,$res);
            $arr=[$res[1],$res[2]];
        }
        $this->assign('id',$id);
        $this->assign('a1',$arr[0]);
        $this->assign('a2',$arr[1]);
        $this->assign('info',$info);
        return $this->fetch();
    }
    //修改奖品表单验证
    public function editform(Request $request){
        $info=input();
        $result = $this->validate($info, 'ScoreProduct');
        if (true !== $result) {
            return $this->error($result);
        }
        // 获取表单上传文件
        $file = $request->file('file');
        $data=[];
        if($file){
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
                $data['score_url']=$filename;
                $plinfo=Db::name('scoreProduct')->field('score_url')->where('id', $info['id'])->find();
                $filename=$plinfo['score_url'];
                $url=ROOT_PATH . 'public' . DS . 'uploads/'.$filename;
                if(file_exists($url)){
                    unlink($url);
                }
            }
        }
        $data['score_product']=$info['score_product'];
        $data['score_describe']=$info['score_describe'];
        $data['score_norms']=$info['score_guige'].$info['score_norms'];
        $data['consume_score']=$info['consume_score'];
        $data['score_num']=$info['score_num'];
        $res=Db::name('scoreProduct')->where('id',$info['id'])->update($data);
        if ($res) {
            return $this->success('修改成功','integral/index');
        } else {
            return $this->error('修改失败');
        }
    }

    //删除奖品
    public function doDel(){
        $id=input('id');
        $res=Db::name('scoreProduct')->delete($id);
        if ($res) {
            return $this->success('删除成功','integral/index');
        } else {
            return $this->error('删除失败');
        }
    }

    //用户兑换列表
    public function socrelist(){
        $join=[
            ['wtq_user u','us.user_id=u.user_id'],
            ['wtq_score_product p','us.sp_id=p.id'],
            ['wtq_address a','us.u_addressid=a.id']
        ];
        $lists=Db::name('useScore')->alias('us')->join($join)
            ->field('u.user_phone,p.score_product,p.score_norms,a.user_address,us.o_state,us.usescore,us.create_time,us.id')
            ->paginate(20);
        $this->assign('lists',$lists);
        return $this->fetch();
    }



}