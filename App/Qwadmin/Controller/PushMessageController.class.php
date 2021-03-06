<?php
namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;

require_once('ThinkPHP/Library/Getui/IGt.Push.php');
require_once('ThinkPHP/Library/Getui/igetui/IGt.AppMessage.php');
require_once('ThinkPHP/Library/Getui/igetui/IGt.APNPayload.php');
require_once('ThinkPHP/Library/Getui/igetui/template/IGt.BaseTemplate.php');
require_once('ThinkPHP/Library/Getui/IGt.Batch.php');
require_once('ThinkPHP/Library/Getui/igetui/utils/AppConditions.php');

define('HOST','http://sdk.open.api.igexin.com/apiex.htm');
// define('Alias','10038');

define('APPKEY',       C('GETUI_CN')['APP_KEY'] );
define('APPID',        C('GETUI_CN')['APP_ID'] );
define('MASTERSECRET', C('GETUI_CN')['MASTER_SECRET'] );
 
class PushMessageController extends ComController {

	private $APPKEY;
	private $APPID;
	private $MASTERSECRET;

	public function index_CN(){
		$region = 'CN';
		$this->assign('region',$region);
		$this->display('index');
	}

	public function index_CN_IOS(){
		$region = 'CN_IOS';
		$this->assign('region',$region);
		$this->display('index');
	}

	public function index_TW(){
		$region = 'TW';
		$this->assign('region',$region);
		$this->display('index');
	}

	public function index_TW_IOS(){
		$region = 'TW_IOS';
		$this->assign('region',$region);
		$this->display('index');
	}

	public function index_BETA(){
		$this->display('index');
	}

	/*
	*个推
	*/
	public function index_Single(){
		$flag = '1'; //个推
		$this->assign('flag',$flag);
		$this->assign('flag0', 'display');
		$this->display('Index');
	}

	/*
	*群推
	*/
	public function index_App(){
		$flag = '2'; //群推
		$this->assign('flag', $flag);
		$this->assign('flag0', 'none');
		$this->display('Index');
	}
	public function push(){
		$region = I('region');
		if($region == 'CN'){//正式-大陆 IOS
			$this->APPKEY 		= C('GETUI_CN')['APP_KEY'];
			$this->APPID  		= C('GETUI_CN')['APP_ID'];
			$this->MASTERSECRET = C('GETUI_CN')['MASTER_SECRET'];
		}elseif($region == 'CN_IOS'){ //正式服iOS
			$this->APPKEY 		= C('GETUI_CN_IOS')['APP_KEY'];
			$this->APPID  		= C('GETUI_CN_IOS')['APP_ID'];
			$this->MASTERSECRET = C('GETUI_CN_IOS')['MASTER_SECRET'];
		}elseif($region == 'TW'){ //正式-台湾
			$this->APPKEY 		= C('GETUI_TW')['APP_KEY'];
			$this->APPID  		= C('GETUI_TW')['APP_ID'];
			$this->MASTERSECRET = C('GETUI_TW')['MASTER_SECRET'];
		}elseif($region == 'TW_IOS'){
			$this->APPKEY 		= C('GETUI_TW_IOS')['APP_KEY'];
			$this->APPID  		= C('GETUI_TW_IOS')['APP_ID'];
			$this->MASTERSECRET = C('GETUI_TW_IOS')['MASTER_SECRET'];
		}else{//大陆-测试
			$this->APPKEY 		= C('GETUI_BETA')['APP_KEY'];
			$this->APPID  		= C('GETUI_BETA')['APP_ID'];
			$this->MASTERSECRET = C('GETUI_BETA')['MASTER_SECRET'];
		}

		if(!$this->APPKEY || !$this->APPID || !$this->MASTERSECRET){
			$this->error('请配置个推参数');
		}
		$title = I('title','','html_entity_decode');
		$content = I('content','','html_entity_decode');
		$offlineTime = I('offlineTime');
		$url = I('url');
		$image = I('image') ; 
		$image = I('image') ? getImage($image) : '';
		$alias = I('alias') ? I('alias') : '';
		
		if(I('ANDROID')){ $OS[] = 'ANDROID'; }
		if(I('IOS')){ $OS[] = 'IOS'; }
		$config = array(
	    	'type'=>2,
	    	'data'=>array(
	    		'time'=>time(),
	    		'fromid'=>1,
	    		'targetid'=>1,
	    		'type'=>2,
	    		'info'=>array(
	    			'id'=>getGetuiID(),
	    			'title'=>$title,
	    			'content'=>$content,
	    			'image'=>$image,
	    			'url'=>$url
	    		),
	    		// 'content'=>$content
	    	)
	    );
	    
	    $flag = I('flag', 1);
	    if ($flag == 1) { 
	    	$this->pushMessageToSingle($config, $alias);
	    } else {
	    	$this->pushMessageToApp($config, $OS, $offlineTime);
	    }
	}

	/*单推接口案例*/
	public function pushMessageToSingle(array $config, $alias){
	    $igt = new \IGeTui(HOST,$this->APPKEY,$this->MASTERSECRET);
	    $template = $this->IGtTransmissionTemplateDemo(json_encode($config));
	    //个推信息体
	    $message = new \IGtSingleMessage();
    	$message->set_isOffline(true);//是否离线
    	$message->set_offlineExpireTime(2*3600*1000);//离线时间
	    $message->set_data($template);//设置推送消息类型

	    $target = new \IGtTarget();
	    $target->set_appId($this->APPID);
	    $target->set_alias($alias);

	    try {
	        $rep = $igt->pushMessageToSingle($message, $target);
	        incGetuiID();
	        var_dump($rep);	
	    }catch(RequestException $e){
	        $requstId =e.getRequestId();
	        $rep = $igt->pushMessageToSingle($message, $target,$requstId);
	        var_dump($rep);
	    }
	}


	// 群推接口案例
	function pushMessageToApp(array $config, array $OS, $offlineTime = 0){
	    $igt = new \IGeTui(HOST,$this->APPKEY,$this->MASTERSECRET);
	    $template = $this->IGtTransmissionTemplateDemo(json_encode($config));
	    //个推信息体
	    //基于应用消息体
	    $message = new \IGtAppMessage();
	    if($offlineTime){
	    	$message->set_isOffline(true);
		    $message->set_offlineExpireTime($offlineTime*3600*1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
	    }else{
	    	$message->set_isOffline(false);
	    }
	    $message->set_data($template);

	    $appIdList = array($this->APPID);
	    $phoneTypeList = $OS;//设置接收的设备系统

	    $cdt = new \AppConditions();
	    $cdt->addCondition(\AppConditions::PHONE_TYPE, $phoneTypeList);
	    $message->set_appIdList($appIdList);
	    $message->set_conditions($cdt->getCondition());

	    $rep = $igt->pushMessageToApp($message, 'task');
	     
	    if($rep['result'] == 'ok'){
	    	incGetuiID();
	    	$this->success('推送成功');
	    }else{
	    	$this->error('推送失败');
	    }
	}

	//对指定列表用户推送消息
	function pushMessageToList(array $config, array $uids, $offlineTime = 0){
	    putenv("gexin_pushList_needDetails=true");
	    putenv("gexin_pushList_needAsync=true");

	    $igt = new \IGeTui(HOST,$this->APPKEY,$this->MASTERSECRET);
	    $template = $this->IGtTransmissionTemplateDemo(json_encode($config));
	    //个推信息体
	    $message = new \IGtListMessage();
	    if($offlineTime){
	    	$message->set_isOffline(true);
		    $message->set_offlineExpireTime($offlineTime*3600*1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
	    }else{
	    	$message->set_isOffline(false);
	    }
	    $message->set_data($template);//设置推送消息类型
	    $contentId = $igt->getContentId($message,"toList任务别名功能");	//根据TaskId设置组名，支持下划线，中文，英文，数字

	    //循环接收方
	    foreach ($uids as $key => $value) {
	    	$targetList[$key] = new \IGtTarget();
		    $targetList[$key]->set_appId($this->APPID);
		    $targetList[$key]->set_alias($value);
	    }
	    
	    $rep = $igt->pushMessageToList($contentId, $targetList);
	    // var_dump($rep);
	    if($rep['result'] == 'ok'){
	    	incGetuiID();
	    	$this->success('推送成功');
	    }else{
	    	$this->error('推送失败');
	    }

	}

	//透传功能模板
	function IGtTransmissionTemplateDemo($config){
	    $template =  new \IGtTransmissionTemplate();
	    $template->set_appId($this->APPID);//应用appid
	    $template->set_appkey($this->APPKEY);//应用appkey
	    $template->set_transmissionType(2);//透传消息类型
	    $template->set_transmissionContent($config);//透传内容
		$config = json_decode($config, true);
		// $template ->set_pushInfo("", "", $config['data']['info']['title'], "", "payload", "", "", "");

		$apn = new \IGtAPNPayload();
		$alertmsg=new \DictionaryAlertMsg();
        $alertmsg->body=$config['data']['info']['content'];
        $alertmsg->actionLocKey="ActionLockey";
        $alertmsg->locKey="LocKey";
        $alertmsg->locArgs=array("locargs");
        $alertmsg->launchImage="launchimage";
		// iOS8.2 支持
        $alertmsg->title=$config['data']['info']['title'];
        $alertmsg->titleLocKey="TitleLocKey";
        $alertmsg->titleLocArgs=array("TitleLocArg");

        $apn->alertMsg=$alertmsg;
        $apn->badge=1;
        $apn->sound="";
        $apn->add_customMsg(array("payload"=>'扑克部落系统消息','type'=>"2"));
		//$apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);

	    return $template;
	}

	function IGtNotificationTemplateDemo($config){
	    $template =  new IGtNotificationTemplate();
	    $template->set_appId($this->APPID);            //应用appid
	    $template->set_appkey($this->APPKEY);          //应用appkey
	    $template->set_transmissionType(1);            //透传消息类型
	    $template->set_transmissionContent($config);   //透传内容;           
	    $template->set_title("个推");                  //通知栏标题
	    $template->set_text("个推最新版点击下载");     //通知栏内容
	    $template->set_logo("");                       //通知栏logo
	    $template->set_logoURL("");                    //通知栏logo链接
	    $template->set_isRing(true);                   //是否响铃
	    $template->set_isVibrate(true);                //是否震动
	    $template->set_isClearable(true);              //通知栏是否可清除

	    return $template;
	}

	public function confirm(){
		try {
            $data = array(
            	'1' => 'gt123',
            	'2' => 'qt123'
            );
            if ($_POST) { 
                $flag = $_POST['flag'];
                $password = $_POST['password'];
                if($data[$flag] == $password){
                    $returnData['status'] = 'ok';
                    $returnData['msg'] = '确认密码正确';
                }else{
                    $returnData['status'] = 'fail';
                    $returnData['msg'] = '确认密码错误';
                }
                $this->ajaxReturn($returnData, 'JSON', 1);
                exit;
                die;
            }
        } catch(Exception $e) {
            error_log('异常' . PHP_EOL, 3, $log);
        } 
    } 
}