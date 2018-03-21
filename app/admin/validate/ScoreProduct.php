<?php
namespace app\admin\validate;
use think\Validate;

class ScoreProduct extends Validate{
    protected $rule =   [
        'score_product'              => 'require',
        'score_norms'              => 'require',
        'consume_score'              => 'require|number',
        'score_num'              => 'require|number',
    ];

    protected $message  =   [
        'score_product.require'      => '奖品名称不能为空',
        'score_norms.require'      => '奖品规格不能为空',
        'score_num.require'       => '奖品数量不能为空',
        'score_num.number'       => '奖品数量应该为整数',
        'consume_score.require'       => '奖品所需积分不能为空',
        'consume_score.number'       => '奖品所需积分应该为整数',
    ];
}