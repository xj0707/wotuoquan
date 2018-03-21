<?php
namespace app\admin\validate;
use think\Validate;

class ProductList extends Validate{
    protected $rule =   [
        'product_name'              => 'require',
        'product_describe'              => 'require|max:600',
       // 'product_norms'              => 'require|max:30',
        'product_price'              => 'require|float',
        'product_num'              => 'require|number',
    ];

    protected $message  =   [
        'product_name.require'      => '产品名称不能为空',
       // 'product_norms.require'      => '产品规格不能为空',
        'product_describe.require'       => '描述不能为空',
        'product_price.require'       => '产品价格不能为空',
        'product_num.require'       => '产品数量不能为空',
        'product_describe.max'       => '描述应该在200个字符以内',
       // 'product_norms.max'       => '规格应该在10个字符以内',
        'product_price.float'       => '价格应该为浮点数',
        'product_num.number'       => '数量应该为整数',
    ];
}