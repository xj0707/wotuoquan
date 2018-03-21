<?php
//配置文件
return [
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    'db_config_sql_server'=>[
        // 数据库类型
        'type'        => 'sqlsrv',
        // 服务器地址
        'hostname'    => '119.29.107.180,5001',
        // 数据库名
        'database'    => 'EWWTQ',
        // 数据库用户名
        'username'    => 'sa',
        // 数据库密码
        'password'    => 'gp193!(#',
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 't_',
    ],

];