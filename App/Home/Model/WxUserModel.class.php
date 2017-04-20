<?php
namespace Home\Model;
use Think\Model;
use Think\Model\RelationModel;

class WxUserModel extends RelationModel{
	//protected $connection = 'DB_CONFIG2';
    public function getUidByOpenid($openid){
        return $this->where(array('username'=>$username, 'os'=>$os))->find();
    }
}