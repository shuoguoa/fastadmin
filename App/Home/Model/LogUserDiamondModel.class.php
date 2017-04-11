<?php
namespace Home\Model;
use Think\Model;

class LogUserDiamondModel extends Model{
    protected $connection = 'DB_CONFIG2';
    protected $trueTableName;

    public function _initialize(){
        $this->trueTableName = 'log_user_diamond'. "_".date("Y-m");
    }
    /**
     * 插入日志
     * @param $uid 用户ID
     * @param $count 消耗数量
     * @param $gid 牌局ID
     * @param $surcharge 服务费
     * @param $balance 余额
     * @param $reason 扣费原因
     * @return bool|string
     */
    public function addLog($uid, $count, $gid, $surcharge, $balance, $reason)
    {
        $data['uid'] = $uid;
        $data['count'] = $count;
        $data['gid'] = $gid;
        $data['surcharge'] = $surcharge;
        $data['balance'] = $balance;
        $data['time'] = time();
        $data['reason'] = $reason;
        return $this->add($data);
    }
}