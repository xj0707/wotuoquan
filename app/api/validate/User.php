<?php
namespace app\api\validate;
use think\Validate;

class User extends Validate{
    // 验证规则
    protected $rule = [
        ['user_phone', 'require|checkPhone', '电话号码不能为空！|电话号码格式不正确'],
        ['user_name', 'require|max:30', '用户名不能为空！|长度不能超过30'],
    ];
}