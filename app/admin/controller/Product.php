<?php
namespace app\admin\controller;

use app\admin\controller\AdminCommon;
use think\Db;
use think\Image;
use think\Request;

class Product extends AdminCommon
{
//所有分类
    public function index()
    {
        $lists = Db::name('product')->where('parent_id', 0)->select();
        $this->assign('lists', $lists);
        return $this->fetch();
    }

//添加一级分类
    public function add()
    {
        return $this->fetch();
    }

//一级分类验证
    public function doAdd()
    {
        $pinfo = input('post.');
        if (!preg_match('/^[a-zA-Z]+$/', $pinfo['product_name'])) {
            return $this->error('不合法的标识符！');
        };
        if (mb_strlen($pinfo['describe'], 'utf8') > 10) {
            return $this->error('描述过长！');
        }
        $info = Db::name('product')->where('product_name', strtoupper($pinfo['product_name']))->find();
        if ($info) {
            return $this->error('该标识符已经存在了！');
        }
        $data = [
            'product_name' => strtoupper($pinfo['product_name']),
            'parent_id' => 0,
            'describe' => $pinfo['describe']
        ];
        $res = Db::name('product')->insert($data);
        if ($res) {
            return $this->success('添加成功', 'product/index');
        } else {
            return $this->error('添加失败');
        }
    }

    //查看对应的二级分类
    public function product1($id = 0)
    {
        $info = input();
        $id = $info['id'];
        $pinfo = Db::name('product')->where('id', $id)->find();
        $lists = Db::name('product')->where('parent_id', $id)->select();
        $this->assign('lists', $lists);
        $this->assign('pinfo', $pinfo);
        $this->assign('id', $id);
        return $this->fetch('product1');
    }

    //添加二级分类
    public function padd($id = 0)
    {
        $info = input();
        $pinfo = Db::name('product')->where('id', $id)->find();
        $this->assign('pinfo', $pinfo);
        $this->assign('parent_id', $info['id']);
        return $this->fetch('add1');
    }

    //二级分类验证
    public function doadd1(Request $request)
    {
        $pinfo = input('post.');
        if (!preg_match('/^[a-zA-Z0-9]+$/', $pinfo['product_name'])) {
            return $this->error('不合法的标识符！');
        };
        if (mb_strlen($pinfo['describe'], 'utf8') > 10) {
            return $this->error('描述过长！');
        }
        $info = Db::name('product')->where('product_name', strtoupper($pinfo['product_name']))->find();
        if ($info) {
            return $this->error('该标识符已经存在了！');
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
            $data = [
                'product_name' => strtoupper($pinfo['product_name']),
                'parent_id' => $pinfo['parent_id'],
                'describe' => $pinfo['describe'],
                'product_url'=>$filename
            ];
            $res = Db::name('product')->insert($data);
            if ($res) {
                return $this->success('添加成功', 'product/index');
            } else {
                return $this->error('添加失败');
            }
        }
    }

    //查看旗下产品列表
    public function product2($id = 0)
    {
        $info = input();
        $id = $info['id'];
        $pinfo = Db::name('product')->where('id', $id)->find();
        $ppinfo = Db::name('product')->where('id', $pinfo['parent_id'])->find();
        $lists = Db::name('productList')->where('product_parentid', $id)->where('p_state',1)->order('id desc')->paginate(20);
        $this->assign('lists', $lists);
        $this->assign('pinfo', $pinfo);
        $this->assign('ppinfo', $ppinfo);
        $this->assign('id', $id);
        return $this->fetch('product2');
    }

    //添加产品
    public function addproduct()
    {
        $info = input();
        $parentid = $info['id'];
        $pinfo = Db::name('product')->where('id', $parentid)->find();
        $this->assign('pinfo', $pinfo);
        $this->assign('parentid', $parentid);
        return $this->fetch('addproduct');
    }

    //添加产品表单验证
    public function doaddform(Request $request)
    {
        $pinfo = input();
        $result = $this->validate($pinfo, 'ProductList');
        if (true !== $result) {
            return $this->error($result);
        }

        // 获取表单上传文件
        $files = $request->file('file');
        $item  = [];
        foreach($files as $file){
            // 上传文件验证
            $result = $this->validate(['file' => $file], ['file' => 'require|image'], ['file.require' => '请选择上传文件', 'file.image' => '非法图像文件']);
            if (true !== $result) {
                $this->error($result);
            }
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            $filename=$info->getSaveName();
            $filename=str_replace('\\','/',$filename);
            $item[]=$filename;
        }
        $str=implode(';',$item);
        if ($info) {
            $data['product_url'] = $str;
            $data['product_name'] = $pinfo['product_name'];
            $data['product_describe'] = $pinfo['product_describe'];
            $data['product_label'] = $pinfo['product_label'];
            $data['product_norms'] = $pinfo['product_guige'].$pinfo['product_norms'];
            $data['product_price'] = $pinfo['product_price'];
            $data['product_num'] = $pinfo['product_num'];
            $data['product_parentid'] = $pinfo['parent_id'];
            $data['contact'] = $pinfo['contact'];
            $res = Db::name('productList')->insert($data);
            if ($res) {
                $pid = Db::name('productList')->getLastInsID();
                //生成二维码
                vendor('phpqrcode.phpqrcode');
                $errorCorrectionLevel=3;
                $matrixPointSize=4;
                $object=new \QRcode();
//                $data=[
//                    'id'=>$pid
//                ];
                $qrname='qrcode/'.$pid.'.png';
                $object->png($pid,$qrname,$errorCorrectionLevel,$matrixPointSize,2);
                Db::name('productList')->where('id',$pid)->update(['qrcode_url'=>$qrname]);
                return $this->success('添加成功', 'product/plist');
            } else {
                return $this->error('添加失败');
            }
        } else {
            // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }

    //修改产品
    public function pedit()
    {
        header('content-type:text/html;charset=utf-8');
        $info = input();
        $pid = $info['id'];
        $pinfo = Db::name('productList')->where('id', $pid)->find();
        $guige=$pinfo['product_norms'];

//        $arr=explode('/',$guige);
//        if(count($arr)!=2){
//            preg_match('/([0-9]+)(.*)/',$guige,$res);
//            $arr=[$res[1],$res[2]];
//        }
//        $this->assign('a1',$arr[0]);
//        $this->assign('a2',$arr[1]);
        $this->assign('guige', $guige);
        $this->assign('pinfo', $pinfo);
        $this->assign('pid', $pid);
        return $this->fetch('pedit');
    }

    //修改产品表单验证
    public function doEditform(Request $request)
    {
        $pinfo = input();
        $result = $this->validate($pinfo, 'ProductList');
        if (true !== $result) {
            return $this->error($result);
        }
        // 获取表单上传文件
        $files = $request->file('file');
        if (count($files)) {
            $item=[];
            foreach($files as $file){
                // 上传文件验证
                $result = $this->validate(['file' => $file], ['file' => 'require|image'], ['file.require' => '请选择上传文件', 'file.image' => '非法图像文件']);
                if (true !== $result) {
                    $this->error($result);
                }
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $filename=$info->getSaveName();
                    $filename=str_replace('\\','/',$filename);
                    $item[]=$filename;
                    $plinfo=Db::name('productList')->field('product_url')->where('id', $pinfo['p_id'])->find();
                    $filenames=explode(';',$plinfo['product_url']);
                    foreach($filenames as $filename){
                        if($filename){
                            $url=ROOT_PATH . 'public' . DS . 'uploads/'.$filename;
                            if(file_exists($url)){
                                unlink($url);
                            }
                        }
                    }
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            $data['product_url']=implode(';',$item);;
        }
        $data['product_name'] = $pinfo['product_name'];
        $data['product_describe'] = $pinfo['product_describe'];
        $data['product_label'] = $pinfo['product_label'];
        //$data['product_norms'] = $pinfo['product_guige'].$pinfo['product_norms'];
        $data['product_price'] = $pinfo['product_price'];
        $data['product_num'] = $pinfo['product_num'];
        $data['contact'] = $pinfo['contact'];
        $res = Db::name('productList')->where('id', $pinfo['p_id'])->update($data);
        if ($res) {
            return $this->success('更新成功', 'product/plist');
        } else {
            return $this->error('更新失败');
        }
    }

    //删除一级分类
    public function doDel()
    {
        $userinfo = session('admin.userinfo');
        if ($userinfo['admin_level'] != 1) {
            return $this->error('你没有该权限！');
        }
        $id = input('id');
        $res = Db::name('product')->delete($id);//删除一级菜单
        //找出二级菜单
        $lists = Db::name('product')->where('parent_id', $id)->select();
        if ($lists) {
            foreach ($lists as $list) {
                Db::name('productList')->where('product_parentid', $list['id'])->delete();//删除产品
            }
        }
        //删除产品
        Db::name('product')->where('parent_id', $id)->delete();
        if ($res) {
            return $this->success('删除成功', 'product/index');
        } else {
            return $this->error('删除失败');
        }
    }

    //删除二级分类
    public function doDel2()
    {
        $userinfo = session('admin.userinfo');
        if ($userinfo['admin_level'] != 1) {
            return $this->error('你没有该权限！');
        }
        $id = input('id');
        //删除产品列表的产品
        Db::name('productList')->where('product_parentid', $id)->delete();
        //删除二级分类
        $res = Db::name('product')->delete($id);
        if ($res) {
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }

    //删除产品
    public function doDel3()
    {
        $userinfo = session('admin.userinfo');
        if ($userinfo['admin_level'] != 1) {
            return $this->error('你没有该权限！');
        }
        $id = input('id');
        $info=Db::name('productList')->field('product_url')->where('id',$id)->find();
        $filename=$info['product_url'];
        $res = Db::name('productList')->delete($id);
        if ($res) {
            if($filename){
                $url=ROOT_PATH . 'public' . DS . 'uploads/'.$filename;
                if(file_exists($url)){
                    unlink($url);
                }
            }
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }

    //产品列表
    public function plist()
    {
        $id=input('id');
        $list=[
            'describe'=>'全部产品'
        ];
        if($id){
            $list=Db::name('product')->where('id', $id)->find();
            $plists = Db::name('productList')->where('product_parentid',$id)->where('p_state',1)->order('id desc')->paginate(20);
        }else{
            $plists = Db::name('productList')->where('p_state',1)->order('id desc')->paginate(20);
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
        $this->assign('list', $list);
        $this->assign('data', $data);
        $this->assign('plists', $plists);
        return $this->fetch('plist');
    }

    //直接添加产品
    public function addproduct1()
    {
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
        $this->assign('data', $data);
        return $this->fetch();
    }

    //产品查询
    public function search(){
        $product_name=input('product_name');
        $plists=Db::name('productList')->where('product_name','like',"%$product_name%")->where('p_state',1)->paginate(20);
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
        $list=[
            'describe'=>'搜索'
        ];
        $this->assign('list', $list);
        $this->assign('data', $data);
        $this->assign('plists', $plists);
        return $this->fetch('plist');
    }

    //设置成推荐产品
    public function phot(){
        $pid=input('pid');
        $content=input('content');
       $res= Db::name('productList')->where('id',$pid)->update(['is_suggest'=>1,'s_describe'=>$content]);
        if($res){
            return $code=1;
        }else{
            return $code=0;
        }
    }

    //取消推荐产品
    public function punhot(){
        $id=input('id');
        $res=Db::name('productList')->where('id',$id)->update(['is_suggest'=>0]);
        if ($res) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }
    }

    //同步思讯产品
    public function sxproduct(){
        //构造类别
        $typeinfos = Db::name('product')->where('parent_id', 0)->select();
        $typedata = array();
        foreach ($typeinfos as $key => $typeinfo) {
            $pinfos = Db::name('product')->where('parent_id', $typeinfo['id'])->select();
            if ($pinfos) {
                foreach ($pinfos as $k => $pinfo) {
                    $typedata[$pinfo['id']]['describe'] = $typeinfo['describe'] . '---' . $pinfo['describe'];
                }
            } else {
                $typedata[$typeinfo['id']]['describe'] = $typeinfo['describe'];
            }
        }
        $mysql=Db::name('productList')->column('item_no');
        $arrinfos=Db::connect('db_config_sql_server1')->query("select  item_no from t_bd_item_info  ");
        $newarr=[];
        foreach ($arrinfos as $arrinfo){
            $newarr[]=$arrinfo['item_no'];
        }
        //获取与思讯的差集，删除这些
        $delarr=array_diff($mysql,$newarr);
        $infos=Db::connect('db_config_sql_server1')->query("select  item_no,item_name,sale_price,item_size,unit_no from t_bd_item_info  ");
        foreach ($infos as $info){
            $item_no=$info['item_no'];
            if(!in_array($item_no,$mysql)){
                $item=Db::connect('db_config_sql_server1')->query("select top (1) * from t_im_branch_stock where item_no='$item_no'");
                $num=0;
                if($item){
                    $num=$item[0]['stock_qty'];
                }
                $data=[
                    'product_name'=>$info['item_name'],
                    'product_price'=>$info['sale_price'],
                    'product_norms'=>$info['item_size'].'/'.$info['unit_no'],
                    'product_num'=>$num,
                    'item_no'=>$item_no,
                    'p_state'=>0//同步过来的没有分类
                ];
                $res=Db::name('productList')->insert($data);
                if($res){
                    $pid = Db::name('productList')->getLastInsID();
                    //生成二维码
                    vendor('phpqrcode.phpqrcode');
                    $errorCorrectionLevel=3;
                    $matrixPointSize=4;
                    $object=new \QRcode();
//                $data=[
//                    'id'=>$pid
//                ];
                    $qrname='qrcode/'.$pid.'.png';
                    $object->png($pid,$qrname,$errorCorrectionLevel,$matrixPointSize,2);
                    Db::name('productList')->where('id',$pid)->update(['qrcode_url'=>$qrname]);
                }
            }
        }
        if(count($delarr)){
            Db::name('productList')->where('item_no','in',$delarr)->delete();
        }
        $pinfos=Db::name('productList')->where('p_state',0)->paginate(20);
        $this->assign('pinfos',$pinfos);
        $this->assign('typedata', $typedata);
        return $this->fetch();
    }

    //思讯同步分类
    public function typelist(){
        $parent_id=input('parent_id');
        $pid=input('id');
        $res=Db::name('productList')->where('id',$pid)->update(['p_state'=>1,'product_parentid'=>$parent_id]);
        if($res){
            $this->success('操作成功','sxproduct','',1);
        }else{
            $this->success('操作失败','sxproduct','',1);
        }
    }






}