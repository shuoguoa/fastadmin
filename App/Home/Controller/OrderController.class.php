<?php
namespace Home\Controller;
use Think\Controller;

class OrderController extends ComController {

    public function index(){
        $openid = session('openid');
        $wxOrderModel = D('WxOrder');
        $sql = 'select b.avatar as avatar,b.nickname as nickname, a.* from wx_order a left join user b on b.id= a.uid where a.openid="'.$openid.
        '" and status = 1 order by a.id desc  limit 20 ';
        $orders = $wxOrderModel->query($sql);
    	
       	$res = array();
        foreach ($orders as $key => $value) {
        	$k = date('Y年m月', $value['time']);
        	$i = $value['date'];
        	$weekarray=array("周 日","周 一","周 二","周 三","周 四","周 五","周 六"); 
        	$w= date('w', $value['time']); 
        	$orders[$key]['w'] = $weekarray[$w];
        	$orders[$key]['d'] = $k;
 
        	$res[$i]['d'] = $k;
        	$month[$k]['month'] = $k;

        	if ($res[$i]['d'] == $k) {
        		$month[$k]['k'][$i]['nickname'] = $value['nickname'];
	        	$month[$k]['k'][$i]['username'] = $value['username'];
	        	$month[$k]['k'][$i]['avatar'] = $value['avatar'];
	        	$month[$k]['k'][$i]['w'] = $weekarray[$w];
	        	$month[$k]['k'][$i]['d'] = $k;
	        	$month[$k]['k'][$i]['rq'] = date('m-d', $value['time']);
	        	$month[$k]['k'][$i]['diamond'] += $value['diamond'];
	        	$month[$k]['k'][$i]['money'] += $value['money'];
        	}
        }  
        $this->assign('month',$month);
        $this->assign('check', I('get.flag'));
        $this->display();
    }

}