<?php
namespace Home\Model;
use Think\Model;

class WxOrderModel extends Model{

    public function getOrderInfo($order_id){
        return $this->where(array('order_id'=>$order_id))->find();
    }
}