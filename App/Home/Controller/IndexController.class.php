<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends ComController {
    
    public function index(){
       $openid = session('openid'); 
        $this->assign('userInfo',array('id'=>0));//设置默认值，不然js过不了
        if($openid){ 
            $wxBindModel = D('WxBind'); 
            $user = $wxBindModel->getCurrentUser($openid); 
            if($user['uid']){
                $userInfo = D('User')->getUserInfoByUid($user['uid']);
                $user['username'] = $userInfo['username'];
                $this->assign('userInfo',$userInfo);//当前绑定用户id
                $allUserInfo = $wxBindModel->getAllUserInfo($openid);
                $this->assign('allUserInfo',$allUserInfo);//当前绑定用户id   
            }
        }
        $goods = $this->getGoods('GOODS_PATH', $user['username']);
        $vips = $this->getGoods('VIP_PATH', $user['username']);
        $this->assign('goods',$goods);
        $this->assign('vips',$vips);
        $flag = I('get.flag') != '' ? I('get.flag') : 1;
        $this->assign('check', $flag);
        $this->display();
    }

    protected function getGoods($path, $username = null){
        $data = $this->pay();
        return $data;
        /*
        $file = C($path);
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
        return $data;*/
    }

    public function switchUser(){
        $openid = session('openid');
        $uid = I('uid', 0, 'intval');
        if($openid && $uid){
            $wxBindModel = D('WxBind');
            $wxBindModel->startTrans();
            $where['openid'] = $openid;
            $where['uid'] = $uid;
            $result1 = $wxBindModel->where($where)->setField('status',1);
            $where['uid'] = array('neq', $uid);
            $result2 = $wxBindModel->where($where)->setField('status',0);
            if($result1 !== false && $result2 !== false){
                $wxBindModel->commit();
                $this->success('切换成功', U('Index/index'));
            }else{
                $wxBindModel->rollback();
                $this->error('切换失败，请重试！', U('Index/index'));
            }
        }
    }
}