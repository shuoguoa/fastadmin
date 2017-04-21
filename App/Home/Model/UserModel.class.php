<?php
namespace Home\Model;
use Think\Model;

class UserModel extends Model{
    protected $connection = 'DB_CONFIG2';

    public function getUserInfo($username, $os){
    	//$re = $this->where(array('username'=>$username, 'os'=>$os))->find(); var_dump($re);exit;
        return $this->where(array('username'=>$username, 'os'=>$os))->find();
    }

    public function getUserInfoByUid($uid){
        return $this->where(array('id'=>$uid))->find();
    }
}