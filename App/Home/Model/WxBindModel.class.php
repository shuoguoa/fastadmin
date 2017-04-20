<?php
namespace Home\Model;
use Think\Model;

class WxBindModel extends Model{
    //protected $connection = 'DB_CONFIG2';
    public function getCurrentUser($openid){
       return $this->where(array('openid'=>$openid, 'status'=>1))->order('id desc')->find();
       // return $this->where(array('uid'=>$uid, 'status'=>1))->find();
    }

    public function getAllUserInfo($openid){
        $uid = $this->where(array('openid'=>$openid))->order('status DESC, bind_time')->getField('uid',true);
        $userModel = D('User');
        $where['id'] = array('in', $uid);
        $order = implode(',', $uid);
        $userInfo = $userModel->where($where)->order("field(id,$order)")->select();
        return $userInfo;
    }
}