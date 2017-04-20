<?php
namespace Home\Model;
use Think\Model;

class UserAmountModel extends Model{
    //protected $connection = 'DB_CONFIG2';

    public function getDiamond($uid){
        return $this->where(array('uid'=>$uid))->getField('diamond');
    }
}