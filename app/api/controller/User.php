<?php
//用户信息接口
namespace app\api\controller;

use think\Db;
use think\Request;
use think\Session;
use think\Image;
class User extends CheckSession
{
    // 获取用户信息
    public function read()
    {
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $uid=Session::get('user_id','api');
        $user_id=input('user_id/d');
        if(!$user_id){
            return json(['code'=>-1,'message'=>'参数不完整']);
        }
        if($uid != $user_id){
            return json(['code'=>-2,'message'=>'发生异常错误，用户ID不匹配']);
        }
        $info=Db::name('user')->where('user_id',$user_id)->find();
        if(!$info){
            return json(['code'=>-3,'message'=>'发生异常错误，用户可能丢失']);
        }
        $address=Db::name('address')->where('user_id',$user_id)->where('address_type',1)->find();
        $dizhi='';
        if($address){
            $dizhi=$address['user_address'];
        }
        $phone=$info['user_type']==1?$info['user_phone']:'第三方登录';
        $data=[
            'user_avatar'=>$info['user_avatar'],
            'user_name'=>$info['user_name'],
            'user_phone'=>$phone,
            'user_address'=>$dizhi
        ];
        return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
    }
    //上传用户头像
    public function upload(Request $request){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $user_id=input('user_id/d');
        if(!$user_id ){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        $file = $request->file('file');
        if($file){
            // 上传图片验证
            if (true !== $this->validate(['file' => $file], ['file' => 'require|image'])) {
                return json(['code'=>-3,'message'=>'图片不合法']);
            }
            // 移动到框架应用根目录/public/uploads/ 目录下
            $fileinfo = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($fileinfo){
//                $plinfo=Db::name('user')->field('user_avatar')->where('id', $user_id)->find();
//                if(!$plinfo){
//                    return json(['code'=>-4,'message'=>'异常错误没有找到该用户']);
//                }
//                $filename=$plinfo['user_avatar'];
//                if($filename){
//                    file_put_contents('bb.txt',$filename);
//                    $url=ROOT_PATH . 'public' . DS . 'uploads/'.$filename;
//                    if(file_exists($url)){
//                        unlink($url);
//                    }
//                }
                $filename=$fileinfo->getSaveName();
                $filename=str_replace('\\','/',$filename);
                $res=Db::name('user')->where('user_id',$user_id)->update(['user_avatar'=>$filename]);
                if($res){
                    return json(['code'=>1,'message'=>'更新成功！']);
                }else{
                    return json(['code'=>-6,'message'=>'操作失败！']);
                }
            }else{
                return json(['code'=>-5,'message'=>'图片上传失败']);
            }
        }
    }

    //用户信息设置
    public function setUserInfo(Request $request){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $uid=Session::get('user_id','api');
        $user_id=input('user_id/d');
        $user_name=input('nickname');
        if(!$user_id || !$user_name){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($uid != $user_id){
            return json(['code'=>-2,'message'=>'发生异常,用户ID不一致！！']);
        }

        $res=Db::name('user')->where('user_id',$user_id)->update(['user_name'=>$user_name]);
        if($res){
            return json(['code'=>1,'message'=>'更新成功！']);
        }else{
            return json(['code'=>-6,'message'=>'操作失败！']);
        }
    }

    //返回用户收货地址
    public function address(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $uid=Session::get('user_id','api');
        $user_id=input('user_id/d');
        if(!$user_id){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($uid != $user_id){
            return json(['code'=>-2,'message'=>'发生异常,用户ID不一致！！']);
        }
        $addinfos=Db::name('address')->where('user_id',$user_id)->order('address_type desc')->select();
        if(count($addinfos)){
            $data=[];
            foreach($addinfos as $key=>$address){
                $data[$key]['address_id']=$address['id'];
                $data[$key]['user_name']=$address['user_name'];
                $data[$key]['user_phone']=$address['user_phone'];
                $data[$key]['user_address']=$address['user_address'];
                $data[$key]['address_type']=$address['address_type'];
            }
            return json(['data'=>$data,'code'=>1,'message'=>'操作成功！']);
        }else{
            return json(['code'=>-3,'message'=>'没有收货地址']);
        }

    }

    //新增收货地址
    public function addAddress(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
        $uid=Session::get('user_id','api');
        $user_id=input('user_id/d');
        $user_name=input('user_name');
        $user_phone=input('user_phone');
        $user_address=input('user_address');
        $type=input('type');
        if(!$user_id || !$user_address){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($uid != $user_id){
            return json(['code'=>-2,'message'=>'发生异常,用户ID不一致！！']);
        }
        $info=Db::name('user')->where('user_id',$user_id)->find();
        if(!$info){
            return json(['code'=>-3,'message'=>'异常错误，玩家以丢失！']);
        }
        $data=[];
        $data['user_id']=$user_id;
        $data['user_name']=empty($user_name)?$info['user_name']:$user_name;
        if($user_phone){
            if(!preg_match("/^1[34578]\d{9}$/", $user_phone)){
                return json(['code'=>-5,'message'=>'电话号码有误！']);
            }
        }
        $data['user_phone']=empty($user_phone)?$info['user_phone']:$user_phone;
        $data['user_address']=$user_address;
        $data['address_type']=$type===1?$type:0;
        $res=Db::name('address')->insert($data);
        if($res){
            return json(['code'=>1,'message'=>'添加成功！']);
        }else{
            return json(['code'=>-4,'message'=>'操作失败！']);
        }
    }

    //编辑或删除收货地址
    public function editAddress(){
        if(!Session::has('user_id','api')){
            return json(['code'=>-100,'message'=>'你还没有登录！']);
        }
       $uid=Session::get('user_id','api');
        $user_id=input('user_id/d');
        $a_id=input('address_id/d');
        $flag=input('flag');
        $user_name=input('user_name');
        $user_phone=input('user_phone');
        $user_address=input('user_address');
        $type=input('type/d');
        if(!$user_id || !$a_id || !$flag){
            return json(['code'=>-1,'message'=>'参数不完整！']);
        }
        if($uid != $user_id){
            return json(['code'=>-2,'message'=>'发生异常,用户ID不一致！！']);
        }
        if($flag==='edit'){
            $data=[];
           if($user_name){
               $data['user_name']=$user_name;
           }
            if($user_phone){
                $data['user_phone']=$user_phone;
            }
            if($user_address){
                $data['user_address']=$user_address;
            }
            if($type==1){
                $data['address_type']=$type;
                Db::name('address')->where('user_id',$user_id)->update(['address_type'=>0]);
                $res=Db::name('address')->where('id',$a_id)->update($data);
                if($res){
                    return json(['code'=>1,'message'=>'更新成功！']);
                }else{
                    return json(['code'=>-3,'message'=>'更新失败！']);
                }
            }elseif($type==0){
                $data['address_type']=$type;
                $res=Db::name('address')->where('id',$a_id)->update($data);
                if($res){
                    return json(['code'=>1,'message'=>'更新成功！']);
                }else{
                    return json(['code'=>-3,'message'=>'更新失败！']);
                }
            }

        }elseif($flag==='del'){
            $res=Db::name('address')->where('id',$a_id)->delete();
            if($res){
                return json(['code'=>1,'message'=>'删除成功！']);
            }else{
                return json(['code'=>-3,'message'=>'删除失败！']);
            }
        }
    }

}