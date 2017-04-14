<?php
namespace Home\Controller;
use Think\Controller;
class FriendController extends ComController {

    public function index(){

        $openid = session('openid');
        $wxBindModel = D('WxBind');
        $user = $wxBindModel->getCurrentUser($openid);
        $goods = $this->getGoods();

        $this->assign('goods',$goods);
        $this->assign('check', I('get.flag'));
        $stateCode = $this->xmlToArray(); 
        $this->assign('stateCode', $stateCode);
        $this->display();
    }

    public function getFriendInfo(){ 
        $username = I('username'); 
        $os = I('os');
        $userModel = D('User');
        $userInfo = $userModel->field('id, username, nickname, os, uuid')->where(array('username'=>$username, 'os'=>$os))->find();
        $data = array(); //var_dump($userInfo);exit;
        if($userInfo){
            $data['status'] =1;
            $data['data'] = $userInfo;
            echo json_encode($data);
        }else{
            $data['status'] =0;
            echo json_encode($data);
        }
    }

    protected function getGoods(){
        $data = $this->pay();
        return $data;
    }
}