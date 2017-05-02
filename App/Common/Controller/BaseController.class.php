<?php
/**
 *
 * 版权所有：恰维网络<qwadmin.qiawei.com>
 * 作    者：寒川<hanchuan@qiawei.com>
 * 日    期：2015-09-17
 * 版    本：1.0.0
 * 功能说明：管理后台模块公共控制器，用于储存公共数据。
 *
 **/

namespace Common\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
        C(setting());
    }
    public function getConnectDb2(){
    	$result = C('DB_CONFIG2');
    	return 'mysql://'.$result['DB_USER'].':'.$result['DB_PWD'].'@'.$result['DB_HOST'].':'.$result['DB_PORT'].'/'.$result['DB_NAME'].'#utf8';
    }

    public function getConnectRedis($param = ''){
    	$redis = new \Redis($param);
		$redis->connect(C('REDIS_HOST'), C('REDIS_PORT'));
		$redis->auth(C('REDIS_AUTH'));
		return $redis;
    }

}