<?php
namespace app\admin\controller;

use app\admin\model\User as UserModel;
use app\common\controller\Common;
use think\Controller;
use think\Db;
use think\Loader;
use think\Request;
use think\Url;
use think\Session;
use think\Config;

/**
* 登录
* @author aierui github  https://github.com/Aierui
* @version 1.0 
*/
class Login extends Common
{
	/**
	 * 后台登录首页
	 */
	public function index()
	{
		if( Session::has('userinfo', 'admin') ) {//判断amdin作用域下面有没有userinfo
			$this->redirect( url('admin/index/index') );
		}
		return view();
	}

	/**
	 * 登录验证
	 */
	public function doLogin(Request $request)
	{
		if( !Request::instance()->isAjax() ) {
			return $this->success( '请求有误！');
		}
		$postData = input('post.');
		$loginData = array(
			'admin_user'=>$postData['user'],
			'admin_pwd'=>$postData['pwd']
		);
		$result = $this->validate($loginData,'Admin');
		if (true !== $result) {
			return $result;
		}
		$admininfo=Db::name('admin')->where('admin_user',$loginData['admin_user'])->where('admin_pwd',md5($loginData['admin_pwd']))->find();
		if(!$admininfo){
			return $this->error('账号或密码错误');
		}
		if($admininfo['admin_type']===0){
			return $this->error('该管理员已被禁用');
		}
		//unset($admininfo['admin_pwd']);
		$ip=$request->ip();
		$time=time();
		$res=Db::name('admin')->where('id',$admininfo['id'])->update(['admin_ip'=>$ip,'update_time'=>$time]);
		Session::set('userinfo', $admininfo, 'admin');
		Session::set('username', $admininfo['admin_user'], 'admin');
		return $this->success('登录成功', url('admin/index/index'));
	}

	/**
	 * 退出登录
	 */
	public function out()
	{
		session::clear('admin');
		return $this->success('退出成功！', url('admin/login/index'));
	}
}