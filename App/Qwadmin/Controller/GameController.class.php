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
class GameController extends ComController
{
    public function index()
    {
        I('id') ? $where['id'] = I('id') : '';
        I('name') ? $where['name'] = I('name') : '';
        I('room_id') ? $where['room_id'] = I('room_id') : '';
        I('status') != '' ? $where['status'] = (I('status') == 0 ? 0 :I('status', '0', 'intval')) : $where['status'] = 0;

        C('DB_PREFIX', '');
        $pagesize = 19;
        $p = intval($_GET['p']) > 0 ? $_GET['p'] : 1;
        $first = $pagesize * ($p - 1);
        $dbModel = M('game', '',$this->getConnectDb2());
        $list = $dbModel->where($where)->limit($first . ',' . $pagesize)->order('id desc')->select();
        $count = $dbModel->where($where)->count('*');
        foreach ($list as $key => $value) {
            $flag = $value['status'] != 3 ? 'display' : 'none';
            $columns[] = array('id' => $value['id'], 'name' => $value['name'], 'display' => $flag);
            $list[$key]['type'] = $value['type'] == '1' ? '游戏' : '高级';
            $list[$key]['match_rebuy_cnt'] = $value['match_rebuy_cnt'] == '1' ? '支持' : '不支持';
            $list[$key]['rest_mode'] = $value['rest_mode'] == '1' ? '支持' : '不支持';
            $list[$key]['match_addon'] = $value['match_addon'] == '1' ? '支持' : '不支持';
            $list[$key]['horde_id'] = $value['horde_id'] == '0' ? '非牌局部落' : $value['horde_id'];
            $list[$key]['club_channel'] = $value['club_channel'] == '1' ? '管理员牌局' : '个人牌局';
            $list[$key]['is_auto'] = $value['is_auto'] == '1' ? '是' : '否';
            $list[$key]['create_time'] = date("Y-m-d H:i:s",$value['create_time']);
            $list[$key]['begin_time'] = $value['begin_time'] !='0' ? date("Y-m-d H:i:s",$value['begin_time']) : 'null';
            $list[$key]['end_time'] = $value['end_time'] !='0' ? date("Y-m-d H:i:s",$value['end_time']) : 'null';
            switch ($value['game_mode']) {
                case '0':
                    $list[$key]['game_mode'] = '普通模式';
                    break;
                case '1':
                    $list[$key]['game_mode'] = 'sng';
                    break;
                case '2':
                    $list[$key]['game_mode'] = 'mtsgn';
                    break;
                case '3':
                    $list[$key]['game_mode'] = 'mtt';
                    break;
            }

            switch ($value['status']) {
                case '0':
                    $list[$key]['status'] = '等待';
                    break;
                case '1':
                    $list[$key]['status'] = '开始';
                    break;
                case '2':
                    $list[$key]['status'] = '结束';
                    break;
                case '3':
                    $list[$key]['status'] = '解散';
                    break;
            }
        }

        $page = new \Think\Page($count, $pagesize);
        if(!empty($where)) {
            foreach ($where as $key => $value) {
                $page->parameter[$key] = urlencode($value);
            }
        }  
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('columns', $columns);
        $this->assign('page', $page);
        $this->assign('status', I('status'));
        $this->display();
    }

    /**
     * 解散牌局
     */
    public function dissolve()  
    {   
       try {
            $log = ROOT_PATH.'/Public/log/geme.log';
            //修改牌局状态
            C('DB_PREFIX', '');
            $dbModel = M('game', '',$this->getConnectDb2());
            if ($_POST) { 
                $id = $_POST['id'];
                $data['status'] = 3;
                if($dbModel->where(array('id'=>$id))->save($data) != false){
                    $dbModel->commit();
                    error_log('牌局解散成功' . PHP_EOL, 3, $log);
                    $returnData['status'] = 'ok';
                    $returnData['msg'] = '牌局解散成功';
                }else{
                    $dbModel->rollback();
                    error_log('牌局解散失败' . PHP_EOL, 3, $log);
                    $returnData['status'] = 'faile';
                    $returnData['msg'] = '牌局解散失败';
                }
                $this->ajaxReturn($returnData, 'JSON', 1);
                exit;
                die;
            }
        } catch(Exception $e) {
            error_log('异常' . PHP_EOL, 3, $log);
        } 
    }   
}

