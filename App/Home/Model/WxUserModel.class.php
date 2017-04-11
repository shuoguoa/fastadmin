<?php
namespace Home\Model;
use Think\Model;
use Think\Model\RelationModel;

class WxUserModel extends RelationModel{

    public function getUidByOpenid($openid){
        return $this->where(array('username'=>$username, 'os'=>$os))->find();
    }
}