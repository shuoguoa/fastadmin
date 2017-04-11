<?php
namespace Getui;

header("Content-Type: text/html; charset=utf-8");

require_once(dirname(__FILE__) . '/' . 'GETUI_PHP_SDK/IGt.Push.php');
require_once(dirname(__FILE__) . '/' . 'GETUI_PHP_SDK/igetui/IGt.AppMessage.php');
require_once(dirname(__FILE__) . '/' . 'GETUI_PHP_SDK/igetui/IGt.APNPayload.php');
require_once(dirname(__FILE__) . '/' . 'GETUI_PHP_SDK/igetui/template/IGt.BaseTemplate.php');
require_once(dirname(__FILE__) . '/' . 'GETUI_PHP_SDK/IGt.Batch.php');
require_once(dirname(__FILE__) . '/' . 'GETUI_PHP_SDK/igetui/utils/AppConditions.php');

class Getui{
	private $_Host;
    private $_AppID;
    private $_AppKey;
    private $_MasterSecret;

	public function __construct($_Host, $_AppID, $_AppKey, $_MasterSecret) {
	    $this->_Host = $_Host;
	    $this->_AppID = $_AppID;
	    $this->_AppKey = $_AppKey;
	    $this->_MasterSecret = $_MasterSecret;
	}
	public function test(){
		echo "string";
	}
	//群推接口案例
	function pushMessageToApp(){
	    $igt = new \IGeTui($this->_Host,$this->_AppKey,$this->_MasterSecret);
	    // $template = IGtTransmissionTemplateDemo();
	    $template = $this->IGtLinkTemplateDemo();
	    //个推信息体
	    //基于应用消息体
	    $message = new \IGtAppMessage();
	    $message->set_isOffline(true);
	    $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
	    $message->set_data($template);

	    $appIdList=array($this->_AppID);
	    

	    $message->set_appIdList($appIdList);

	    $rep = $igt->pushMessageToApp($message,"任务组名");

	    var_dump($rep);
	}

	function IGtLinkTemplateDemo(){
	    $template =  new \IGtLinkTemplate();
	    $template ->set_appId($this->_AppID);//应用appid
	    $template ->set_appkey($this->_AppKey);//应用appkey
	    $template ->set_title("请输入通知标题");//通知栏标题
	    $template ->set_text("请输入通知内容");//通知栏内容
	    $template ->set_logo("");//通知栏logo
	    $template ->set_isRing(true);//是否响铃
	    $template ->set_isVibrate(true);//是否震动
	    $template ->set_isClearable(true);//通知栏是否可清除
	    $template ->set_url("http://www.igetui.com/");//打开连接地址
	    return $template;
	}
}

define('HOST','http://sdk.open.api.igexin.com/apiex.htm');
define('APPKEY','TQdVlF8Hhu877M83HoIcy');
define('APPID','9CZM7qdaag9aNig5HmX0g9');
define('MASTERSECRET','lkql1buecRANBG3aOcs4q5');
$getui = new Getui(HOST, APPID, APPKEY, MASTERSECRET);
$getui->pushMessageToApp();