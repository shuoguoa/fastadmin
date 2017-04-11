<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-17
* 版    本：1.0.0
* 功能说明：模块公共文件。
*
**/


function UpImage($callBack="image",$width=100,$height=100,$image=""){
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" width='.$width.' height="'.$height.'"  src="'.U('Upload/uploadpic').'?Width='.$width.'&Height='.$height.'&BackCall='.$callBack.'&Img='.$image.'"></iframe>
         <input type="hidden" name="'.$callBack.'" id="'.$callBack.'">';
}
function BatchImage($callBack="image",$height=300,$image=""){
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" src="'.U('Upload/batchpic').'?BackCall='.$callBack.'&Img='.$image.'"></iframe>
		<input type="hidden" name="'.$callBack.'" id="'.$callBack.'">';
}


/*
 * 函数：网站配置获取函数
 * @param  string $k      可选，配置名称
 * @return array          用户数据
*/
function setting($k=''){
	if($k==''){
        $setting =M('setting')->field('k,v')->select();
		foreach($setting as $k=>$v){
			$config[$v['k']] = $v['v'];
		}
		return $config;
	}else{
		$model = M('setting');
		$result=$model->where("k='{$k}'")->find(); 
		return $result['v'];
	}
}

/**
 * 函数：格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 函数：加密
 * @param string            密码
 * @return string           加密后的密码
 */
function password($password){
	/*
	*后续整强有力的加密函数
	*/
	return md5('Q'.$password.'W');

}

/**
 * 随机字符
 * @param number $length 长度
 * @param string $type 类型
 * @param number $convert 转换大小写
 * @return string
 */
function random($length=6, $type='string', $convert=0){
    $config = array(
        'number'=>'1234567890',
        'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );
    
    if(!isset($config[$type])) $type = 'string';
    $string = $config[$type];
    
    $code = '';
    $strlen = strlen($string) -1;
    for($i = 0; $i < $length; $i++){
        $code .= $string{mt_rand(0, $strlen)};
    }
    if(!empty($convert)){
        $code = ($convert > 0)? strtoupper($code) : strtolower($code);
    }
    return $code;
}

//连接redis
function redis(){
    $redis = new \Redis();
    $redis->connect(C('REDIS_HOST'), C('REDIS_PORT'));
    $redis->auth(C('REDIS_AUTH'));
    return $redis;
}

//
function fn_check_code($code)
{
    $len = strlen($code)-2;
    $pattern1 = '/^(?:0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){'.$len.'}\d/';
    @preg_match($pattern1,$code,$arr1);
    if (!empty($arr1[0]))
    {
        return true;
    }
    $pattern2 = '/(?:0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){'.$len.'}\d$/';
    @preg_match($pattern2,$code,$arr2);
    if (!empty($arr2[0]))
    {
        return true;
    }

    $pattern4 = '/^(?:9(?=8)|8(?=7)|7(?=6)|6(?=5)|5(?=4)|4(?=3)|3(?=2)|2(?=1)|1(?=0)){'.$len.'}\d/';
    @preg_match($pattern4,$code,$arr4);
    if (!empty($arr4[0]))
    {
        return true;
    }


    $pattern5 = '/(?:9(?=8)|8(?=7)|7(?=6)|6(?=5)|5(?=4)|4(?=3)|3(?=2)|2(?=1)|1(?=0)){'.$len.'}\d$/';
    @preg_match($pattern5,$code,$arr5);

    if (!empty($arr5[0]))
    {
        return true;
    }
    return false;
}

/**
 * @param $phone
 * @return bool
 */
function fn_is_mobile($phone)
{
    $len = strlen($phone);
    if ($len > 1 && $len < 13) {
        return true;
    } else {
        return false;
    }
}

function fn_chinese_lenth($name)
{
    preg_match_all("/[\x{4e00}-\x{9fa5}]+/u", $name, $matchs);
    $str = '';
    foreach ($matchs[0] as $key => $m) {
        $str .= $m;
    }
    $character_len = strlen($name) - strlen($str);
    //utf-8的中文字符占3个字节，转为2个字节计算
    $china_len = strlen($str) / 3 * 2;
    return $character_len + $china_len;
}

function fn_is_chinese($name)
{
    if (preg_match('/^[\x{4e00}-\x{9fa5}\x{ff00}-\x{ffef}\x{3000}-\x{303f}\x{00}-\x{ff}\w\s]+$/u', $name)) {
        return true;
    } else {
        return false;
    }
}

function fn_send2netease($action, $data)
{
    $_config = C('NETEASE');
    $api = 'https://api.netease.im/' . $action;
    $key = trim($_config['APP_KEY']);
    $nonce = fn_user_salt();
    $curtime = time();
    $secret = trim($_config['APP_SECRET']);
    $checksum = SHA1($secret . $nonce . $curtime);
    $header = array("AppKey: $key", "Nonce: $nonce", "CurTime: $curtime", "CheckSum: $checksum", "Content-Type: application/x-www-form-urlencoded;charset=utf-8");
    $res = fn_request_post($api, $data, $header);
    $rs = json_decode($res, true);
    return $rs;
}

function fn_user_salt()
{
    $salt = '';
    for ($i = 0; $i < 30; $i++) {
        $salt .= chr(rand(33, 126));
    }
    return addslashes($salt);
}

function fn_request_post($url = "", $param = "", $header = array())
{
    if (empty($url) || empty($param)) {
        return false;
    }
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    // 增加 HTTP Header（头）里的字段
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // 终止从服务端进行验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data = curl_exec($ch);//运行curl
    if ($data === false) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    } else {
        curl_close($ch);
        return $data;
    }
}

/**
 * 获取图片完全地址
 */
function getImage($img){
    if (stristr($img, 'http')) {
        return $img;
    }else{
        // return 'http://'.$_SERVER["SERVER_NAME"].'/dezhou'.$img; //测试环境
        return 'http://'.$_SERVER["SERVER_NAME"].''.$img; //正式环境
    }
}

/**
 * 获取个推已推送ID
 */
function getGetuiID(){
    return M('setting')->where(array('k' => 'GETUI_ID'))->getField('v');
}

/**
 * 个推ID加1
 */

function incGetuiID(){
    M('setting')->where(array('k' => 'GETUI_ID'))->setInc('v');
}