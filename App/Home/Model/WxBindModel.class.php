<?php
namespace Home\Model;
use Think\Model;

class WxBindModel extends Model{

    public function getCurrentUser($openid){
     /*
        $model = M('user');
        $model->where(array('openid'=>$openid, 'status'=>1))->find();echo 888; echo $model->_sql();exit;
        // $this->where(array('openid'=>$openid, 'status'=>1))->find();echo 888; echo $this->_sql();exit;*/
       return $this->where(array('openid'=>$openid, 'status'=>1))->find();
        //echo 9999;exit;
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