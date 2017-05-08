<?php
namespace Home\Controller;
use Think\Controller;

class OrderController extends ComController {

    public function index(){
        $uid = session('uid'); 
        if ($uid != '') {
            $wxOrderModel = D('WxOrder'); 
            $bgn = strtotime(date('Y-m', strtotime('-1 month')));
            $end = strtotime(date('Y-m-d H:i:s', time()));

            $pageSize = 8;
            $firstRow = $_GET['p'] == null ? '0' : ($_GET['p']-1)*$pageSize;

            $field = 'b.avatar as avatar,b.nickname as nickname, a.time, a.date, a.diamond, a.money, a.username';
            $sql = 'select '.$field.' from wx_order a left join user b on b.id= a.uid where a.uid="'.$uid.
            '" and status = 1 and time between '.$bgn.' and '.$end.' order by a.id desc  limit '.$firstRow.' ,'. $pageSize;
            $orders = $wxOrderModel->query($sql); 
            $sql2 = 'select count(*) as num from wx_order  where uid="'.$uid.
            '" and status = 1 and time between '.$bgn.' and '.$end;

            $count = $wxOrderModel->query($sql2); 
            $this->assign('num', $count[0]['num']);
            $page = new \Think\Page($count[0]['num'], $pageSize);
            $page = $page->show();
            $this->assign('page', $page);

            $res = array();
            $month = array();
            foreach ($orders as $key => $value) {
                $k = date('Y年m月', $value['time']);
                $i = $value['date'];
                $weekarray=array("周 日","周 一","周 二","周 三","周 四","周 五","周 六"); 
                $w= date('w', $value['time']); 
                $orders[$key]['w'] = $weekarray[$w];
                $orders[$key]['d'] = $k;
                $orders[$key]['rq'] = date('m-d', $value['time']);
                
                $orders[$key]['avatar'] = $value['avatar'] == '' ? $this->getDefaultAvatar() : $value['avatar'];

                $res[$i]['d'] = $k;
                $month[] = $k;
            }  
        } else {
           $this->redirect('Index/index', '', 3,'获取不到uid,请重新登录，点“切换按扭”选本账号即可重新登录~');
        }
        $month = array_unique($month);
        $month = array_reverse($month);
        $month = implode($month, '~');
        $this->assign('month',$month);
        $this->assign('orders', $orders);
        $this->assign('check', I('get.flag'));
        $this->display();
    }

}