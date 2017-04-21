<?php
namespace Home\Model;
use Think\Model;

class WxOrderModel extends Model{
	protected $connection = 'DB_CONFIG2';
    public function getOrderInfo($order_id){
        return $this->where(array('order_id'=>$order_id))->find();
    }
}