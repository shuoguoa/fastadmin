<?php
/**
 *
 * 版权所有：恰维网络<qwadmin.qiawei.com>
 * 作    者：寒川<hanchuan@qiawei.com>
 * 日    期：2015-09-15
 * 版    本：1.0.0
 * 功能说明：配置文件。
 *
 **/
return array(
    //网站配置信息
    'URL' => 'http://www.qwadmin.com', //网站根URL
    'COOKIE_SALT' => '2333', //设置cookie加密密钥
    //备份配置
    'DB_PATH_NAME' => 'db',        //备份目录名称,主要是为了创建备份目录
    'DB_PATH' => './db/',     //数据库备份路径必须以 / 结尾；
    'DB_PART' => '20971520',  //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
    'DB_COMPRESS' => '1',         //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
    'DB_LEVEL' => '9',         //压缩级别   1:普通   4:一般   9:最高
    //扩展配置文件
    'LOAD_EXT_CONFIG' => 'db2',
    //'URL_MODEL'=>1,

    //数据库配置2
    'DB_CONFIG2' => array(
        'DB_TYPE'   => 'mysql',          // 数据库类型
        'DB_HOST'   => '192.168.0.156',  // 服务器地址
        'DB_NAME'   => 'nutsporker', // 数据库名
        'DB_USER'   => 'liufuqing',    // 用户名
        'DB_PWD'    => 'Liufuqing!2sql',    // 密码
        'DB_PORT'   => 30306,            // 端口
        'DB_CHARSET'=> 'utf8',           // 数据库编码默认采用utf8
    ),
    
    //云信配置
    'NETEASE' => array(
        'APP_KEY'    => '12a012c762dbcc835b825e728c52e0b1',
        'APP_SECRET' => '8f27707637f8'
    ),

    //个推大陆配置
    'GETUI_CN' => array(
        'APP_KEY'       => '5TdKabYayX6INx80w5v2g7',
        'APP_ID'        => 'Lo3mEjH0RbAUoeFviFPDj8',
        'MASTER_SECRET' => 'HsZhohKfzk7eyQGIrbwP2'
    ),

    //个推台湾配置
    'GETUI_TW' => array(
        'APP_KEY'       => '5TdKabYayX6INx80w5v2g7',
        'APP_ID'        => 'Lo3mEjH0RbAUoeFviFPDj8',
        'MASTER_SECRET' => 'HsZhohKfzk7eyQGIrbwP2'
    ),

    /*个推测试服配置
    'GETUI_BETA' => array(
        'APP_KEY'       => 'CErmKdb6xO7qV2hJ9o0MQ5',
        'APP_ID'        => 'pQU1PpWli97vxbw8SSJNCA',
        'MASTER_SECRET' => 'V2GPxXpyom8o9BZSaC2UT4'
    )*/
    'GETUI_BETA' => array(
        'APP_KEY'       => '5TdKabYayX6INx80w5v2g7',
        'APP_ID'        => 'Lo3mEjH0RbAUoeFviFPDj8',
        'MASTER_SECRET' => 'HsZhohKfzk7eyQGIrbwP2'
    )
);