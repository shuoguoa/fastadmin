<?php
/**
 *
 * 版权所有：恰维网络<qwadmin.qiawei.com>
 * 作    者：寒川<hanchuan@qiawei.com>
 * 日    期：2016-01-20
 * 版    本：1.0.0
 * 功能说明：用户控制器。
 *
 **/
namespace Qwadmin\Controller;
use Think\Controller;
class DataStatController extends ComController
{
    public function index()
    {
        $this->display();
    }
    public function insurance(){
        I('server') ? $where['server'] = I('id') : '';

        $bgn_time = strtotime(date('Y-m-d', strtotime('-1 day')));
        $end_time = strtotime(date('Y-m-d', strtotime('0 day')));
        $where['time'] = array('between', array($bgn_time, $end_time)); 

        C('DB_PREFIX', '');
        $dbModel = M('tbl_insurance_stat');
        $list = $dbModel->where($where)->order('date asc')->select();
        $result = array();
        foreach ($list as $key => $value) {
            $index = date('Y-m-d',$value['time']);
            $result[$index]['time'] = $index;
            $result[$index]['money'] += $value['money'];
        } 
        $xlist = array();
        $ylist = array();
        $point = array();
        $script = array();

        foreach ($result as $key => $value) {
            $xlist['time'][]  = $value['time'];
            $xlist['birthday'][]  = $value['time'];
            $ylist[] = $value['money'];
            $point[] = $value['money'];
            $script[] = $value['time']."<br/>".$value['money'];
        }

        $xlist['time'] = array(1,2,3,4,5,6,7);
        $xlist['birthday'] = array(1,2,3,4,5,6,7);
        $ylist = array(1,2,3,4,5,6,7);
        $point = $ylist;
        $script = $xlist."<br/>".$ylist;

        $title = '保险赔率统计';
        $this->assign('title', $title);
        $this->assign('bgn_time', $bgn_time ? date('Y-m-d',$bgn_time) : '');
        $this->assign('end_time', $end_time ? date('Y-m-d',$end_time) : '');
        $this->assign('xlistId', json_encode($xlist['time']));
        $this->assign('xlistBir', json_encode($xlist['birthday']));
        $this->assign('ylist', json_encode($ylist));
        $this->assign('point', $point);
        $this->assign('script', json_encode($script));
        $this->assign('country', $country);
        $this->display();
    }  
    /*
    *从redis 队列读取数据存入数据表
    */
    public function getDataByRedisIntoTable(){
        $redis = $this->getConnectRedis();
        $keyNames = I('keyNum') != '' ? I('keyNum') : $setList[0];
        $redis->select(1);
        $redisInfo = $redis->lRange($listKey, 0, 5);
        $dataLength = $redis->lLen($listKey);

        $list = $redis->smembers($keyNames); 
        $list = json_decode($list, true);
        foreach ($list as $key => $value) {
            $data[] = array('server_group' => $value[''], 'server_name'=> $value[''], 'uid' => $value[''], 'code' => $value[''],
                'gid' => $value[''], 'buy'=> $value[''], 'pay' => $value[''], 'create_time' => $value['']);
            $data2[] = array('server_group' => $value[''], 'server_name'=> $value[''], 'uid' => $value[''], 'code' => $value[''],
                'gid' => $value[''], 'buy'=> $value[''], 'pay' => $value[''], 'create_time' => $value['']);
        }
        $model = M('tbl_insurance');
        $result = $model->addAll($data);
        $model2 = M('tbl_insurance_stat');
        $result2 = $model->addAll($data2);
        if (!$result) {
            $model->rollback();
        } else {
            $model->commit();
        }
    }

}

