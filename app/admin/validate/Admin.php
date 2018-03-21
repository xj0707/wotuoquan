<?php
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{

    protected $rule =   [
        'admin_user'              => 'require',
        'admin_pwd'              => 'require|length:6,16',
    ];

    protected $message  =   [
        'admin_user.require'      => '用户名不能为空',
        'admin_pwd.length'       => '请输入正确的密码格式',
        'admin_pwd.require'       => '密码不能为空',
    ];

    protected $scene = [
        'add' => ['admin_user','admin_pwd'],
        'login' =>  ['admin_user','admin_pwd'],
        'edit' => ['mobile', 'admin_pwd']
    ];

}


