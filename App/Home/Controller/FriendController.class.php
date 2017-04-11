<?php
namespace Home\Controller;
use Think\Controller;
class FriendController extends ComController {

    public function index(){

        $openid = session('openid');
        $wxBindModel = D('WxBind');
        $user = $wxBindModel->getCurrentUser($openid);
        $goods = $this->getGoods('FRIEND_PATH', $user['username']);
        $vips = $this->getGoods('VIP_PATH', $user['username']);
        $this->assign('goods',$goods);
        $this->assign('vips',$vips);
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
        $data = array();
        if($userInfo){
            $data['status'] =1;
            $data['data'] = $userInfo;
            echo json_encode($data);
        }else{
            $data['status'] =0;
            echo json_encode($data);
        }
    }

    protected function getGoods($path, $username = null){
       /* $file = C($path);
        $xml = simplexml_load_file($file);
        $xml = json_decode(json_encode($xml), true);
        $data = array();
        if($username){
            $wxDiscountModel = D('WxDiscount');
            $discount = $wxDiscountModel->where(array('username'=>$username))->getField('discount');
        }
        foreach ($xml as $key => $value) {
            if($username && $discount && $path != "VIP_PATH"){
                $value['price'] = round($discount * $value['original_price'], 2);
            }else{
                $value['price'] = round($value['discount'] * $value['original_price'], 2);
            }
            $data[$value['id']] = $value;
            unset($value);
        }
        $data = array_values($data);*/
        $data = $this->pay();
        return $data;
    }
}