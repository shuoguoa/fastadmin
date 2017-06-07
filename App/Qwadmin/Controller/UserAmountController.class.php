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
class UserAmountController extends ComController
{
    public function index()
    {
        I('uid') ? $where['user.id'] = I('uid') : '';
        I('nickname') ? $where['user.nickname'] = I('nickname') : '';
        I('iphone') ? $where['user.username'] = I('iphone') : '';
        I('uuid') ? $where['user.uuid'] = I('uuid') : '';

        C('DB_PREFIX', '');
        $pagesize = 15;
        $p = intval($_GET['p']) > 0 ? $_GET['p'] : 1;
        $first = $pagesize * ($p - 1);
        $dbModel = M('user', '',$this->getConnectDb2());
        $list = $dbModel->field('b.coins as coins, b.diamond as diamond, user.*')->join('user_amount b on b.uid=user.id')->where($where)->limit($first . ',' . $pagesize)->order('user.id desc')->select();

        $count = $dbModel->where($where)->count('*');

        foreach ($list as $key => $value) {
            $list[$key]['os'] = $value['os'] == '1' ? 'Android' : 'IOS';
            $list[$key]['account_type'] = $value['account_type'] == 1 ? 'facebook' : ($value['account_type'] == 2 ? 'twitter' : '默认账号');
            $list[$key]['status'] = $value['is_block'] == 0 ? '冻结' : '解冻';
        }

        $page = new \Think\Page($count, $pagesize);
        if(!empty($where)) {
            foreach ($where as $key => $value) {
                $page->parameter[$key] = urlencode($value);
            }
        } 
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    /**
     * 俱乐部列表
     */
    public function club()  
    {
        I('vid') ? $where['vid'] = I('vid') : '';
        I('tname') ? $where['tname'] = I('tname') : '';

        C('DB_PREFIX', '');
        $pagesize = 18;
        $p = intval($_GET['p']) > 0 ? $_GET['p'] : 1;
        $first = $pagesize * ($p - 1);
        $dbModel = M('team', '',$this->getConnectDb2());
        $list = $dbModel->where($where)->limit($first . ',' . $pagesize)->order('create_time desc')->select();
        $count = $dbModel->where($where)->count('*');
        foreach ($list as $key => $value) {
            $list[$key]['create_time'] = $value['create_time'] != 0 ? date('Y-m-d H:i:s', $value['create_time']) : '';
            $list[$key]['is_owner'] = $value['is_owner'] == 1 ? '只能群主开局' : '';
            $list[$key]['is_private'] = $value['is_private'] == 1 ? '否' : '是';
            $list[$key]['modify_time'] = $value['modify_time'] != 0 ? date('Y-m-d H:i:s', $value['modify_time']) : '';
        }
        $page = new \Think\Page($count, $pagesize);
        if(!empty($where)) {
            foreach ($where as $key => $value) {
                $page->parameter[$key] = urlencode($value);
            }
        } 
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    /**
     * 修改俱乐部vid
     */
    public function updatevid() { 
        if ($_POST) { 
            $vid = $_POST['vid'];
            $tid = $_POST['tid'];
            $owner = $_POST['owner'];
            if ($vid == null || $tid == null || $owner == null) {
                $this->error('params error');
            }

            C('DB_PREFIX', '');
            $team_model = M('team', '',$this->getConnectDb2());
            $up_data = $team_model->where('tid = '.$tid)->select();
            if ($up_data == null) {
                $this->error('Game update team ID failed');
            } else {
                $up_data = $up_data[0];
            }
            $send_data = 'owner=' . $owner . '&tid=' . $tid;

            $custom['is_owner'] = $up_data['is_owner'];
            $custom['update_type'] = 0;
            $custom['is_private'] = $up_data['is_private'];
            $custom['area_id'] = $up_data['area_id'];
            $custom['avatar'] = $up_data['avatar'];
            $custom['count'] = $up_data['count'];
            $custom['end_date'] = $up_data['end_date'];
            $custom['modify_time'] = $up_data['modify_time'];
            $custom['vid'] = $data['vid'] = $vid;
            $send_data .= '&custom=' . json_encode($custom);
            $where['tid'] = $tid;
            $where['owner'] = $owner;
            if ($team_model->where($where)->save($data) !== false) {
                $api_action = 'nimserver/team/update.action';
                $res = fn_send2netease($api_action, $send_data);
                if ($res['code'] == 200) {
                    $returnData['status'] = 'ok';
                    $returnData['msg'] = 'ID修改成功';
                } else {
                    $returnData['status'] = 'faile';
                    $returnData['msg'] = 'ID修改失败';
                }
                $this->ajaxReturn($returnData, 'JSON', 1);
                exit;
                die;
            } else {
                $this->error('Game update team ID failed');
            }
        }
    }

    /**
     * 部落列表
     */
    public function horde()  
    {
        I('vid') ? $where['horde_vid'] = I('vid') : '';
        I('name') ? $where['name'] = I('name') : '';

        C('DB_PREFIX', '');
        $pagesize = 18;
        $p = intval($_GET['p']) > 0 ? $_GET['p'] : 1;
        $first = $pagesize * ($p - 1);
        $dbModel = M('horde', '',$this->getConnectDb2());
        $list = $dbModel->where($where)->limit($first . ',' . $pagesize)->order('ctime desc')->select();
        $count = $dbModel->where($where)->count('*');
        foreach ($list as $key => $value) {
            $list[$key]['ctime'] = $value['ctime'] != 0 ? date('Y-m-d H:i:s', $value['ctime']) : '';
            $list[$key]['is_control'] = $value['is_control'] == 1 ? '开' : '关';
            $list[$key]['is_modify'] = $value['is_modify'] == 0 ? '否' : '是';
        }
        $page = new \Think\Page($count, $pagesize);
        if(!empty($where)) {
            foreach ($where as $key => $value) {
                $page->parameter[$key] = urlencode($value);
            }
        } 
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    /**
     * 修改部落vid
     */
    public function updateHordeVid() { 
        if ($_POST) { 
            $vid = $_POST['vid'];
            $id = $_POST['id'];
            $vid ? $data['horde_vid'] = $vid : '';
            $id ? $where['id'] = $id : '';
            if ($vid == null || $id == null) {
                $this->error('params error');
            }

            C('DB_PREFIX', '');
            $horde_model = M('horde', '',$this->getConnectDb2());
            if ($horde_model->where($where)->save($data) !== false) {
                $returnData['status'] = 'ok';
                $returnData['msg'] = 'ID修改成功';
            } else {
                $returnData['status'] = 'faile';
                $returnData['msg'] = 'ID修改失败';
            }
            $this->ajaxReturn($returnData, 'JSON', 1);
            exit;
            die;
        }
    }
    
    /**
     * 支付宝，app充值
     */
    public function order()  
    {
        I('id') ? $where['id'] = I('id') : '';
        I('uid') ? $where['uid'] = I('uid') : '';

        C('DB_PREFIX', '');
        $pagesize = 18;
        $p = intval($_GET['p']) > 0 ? $_GET['p'] : 1;
        $first = $pagesize * ($p - 1);
        $dbModel = M('payment', '',$this->getConnectDb2());
        $list = $dbModel->where($where)->limit($first . ',' . $pagesize)->order('time desc')->select();
        $count = $dbModel->where($where)->count('*');
        foreach ($list as $key => $value) {
            $list[$key]['time'] = $value['time'] != 0 ? date('Y-m-d H:i:s', $value['time']) : '';
            $list[$key]['success_time'] = $value['success_time'] != 0 ? date('Y-m-d H:i:s', $value['success_time']) : '';
            $list[$key]['invoice'] = $value['invoice'] == 1 ? '有发票' : '不需要发票';
            $list[$key]['channel'] = $value['channel'] == 1 ? '支付宝' : ($value['channel'] == 2 ? 'ios' : '微信');
            $list[$key]['status'] = $value['status'] == 1 ? '充值成功' : '默认充值成功';
            $list[$key]['platform'] = $value['platform'] == 1 ? 'pc平台' : 'ios';
        }
        $page = new \Think\Page($count, $pagesize);
        if(!empty($where)) {
            foreach ($where as $key => $value) {
                $page->parameter[$key] = urlencode($value);
            }
        } 
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    /**
     * 微信公众号充值
     */
    public function OfficialAccountsOrder()  
    {
        I('id') ? $where['id'] = I('id') : '';
        I('uid') ? $where['uid'] = I('uid') : '';

        C('DB_PREFIX', '');
        $pagesize = 18;
        $p = intval($_GET['p']) > 0 ? $_GET['p'] : 1;
        $first = $pagesize * ($p - 1);
        $dbModel = M('wx_order', '',$this->getConnectDb2());
        $list = $dbModel->where($where)->limit($first . ',' . $pagesize)->order('time desc')->select();
        $count = $dbModel->where($where)->count('*');
        foreach ($list as $key => $value) {
            $list[$key]['time'] = $value['time'] != 0 ? date('Y-m-d H:i:s', $value['time']) : '';
            $list[$key]['success_time'] = $value['success_time'] != 0 ? date('Y-m-d H:i:s', $value['success_time']) : '';
            $list[$key]['status'] = $value['status'] == 1 ? '充值成功' : '默认充值成功';
            $list[$key]['platform'] = '微信公众号充值';
        }

        $page = new \Think\Page($count, $pagesize);
        if(!empty($where)) {
            foreach ($where as $key => $value) {
                $page->parameter[$key] = urlencode($value);
            }
        } 
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    } 


    public function getCountlyGraph(){
        I('id') ? $where['id'] = I('id') : '';
        I('uid') ? $where['uid'] = I('uid') : '';
        I('flag') ? $where['flag'] = I('flag') : '';
        $table = I('flag') == 1 ? 'payment' : 'wx_order';
        $field = I('flag') == 1 ? 'uid, money, time, platform' : 'uid, money, time';
        $bgn_time = strtotime('-2 month');
        $end_time = time();
        $where['time'] = array('between', array($bgn_time, $end_time)); 
        $where['status'] = 1;
        C('DB_PREFIX', '');
        $dbModel = M($table, '',$this->getConnectDb2());
        $list = $dbModel->field($field)->where($where)->limit($first . ',' . $pagesize)->order('time asc')->select();
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
        
        $title = I('flag') == 1 ? '平台充值统计' : '公众号充值统计';
        $action = I('flag') == 1 ? 'Order' : 'OfficialAccountsOrder';
        $this->assign('title', $title);
        $this->assign('action', $action);
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

    /**
     * 充值钻石更新钻石记录表，账户表钻石
     */
    public function rechargeDia()  
    {   
       try {
            $log = ROOT_PATH.'/Public/log/wxpay.log';
            //更新钻石余额
            C('DB_PREFIX', '');
            $dbModel = M('user_amount', '',$this->getConnectDb2());
            $dbModel2 = M('log_user_diamond', '',$this->getConnectDb2());
            if ($_POST) { 
                $uid = $_POST['uid'];
                $os =  $_POST['os'];
                $diamond =  $_POST['diamond']; 

                if($dbModel->where(array('uid'=>$uid))->setInc('diamond', $diamond) != false){
                    //获取钻石余额
                    $userDiamond = $dbModel->field('diamond')->where(array('uid'=>$uid))->find();
                    if($userDiamond === false){
                        $dbModel->rollback();
                        error_log('获取余额失败' . PHP_EOL, 3, $log);
                    }
                    //插入钻石记录表
                    $data['uid'] = $uid;
                    $data['count'] = $diamond;
                    $data['gid'] = 0;
                    $data['surcharge'] = 0;
                    $data['balance'] = $userDiamond['diamond'];
                    $data['time'] = time();
                    $data['reason'] = 11;
                    if($dbModel2->add($data) === false){
                        $dbModel->rollback();
                        error_log('插入log_user_diamond记录表失败' . PHP_EOL, 3, $log);
                    }
                    //更新redis
                    $redis = $this->getConnectRedis();
                    $redisKey = 'user:' . $uid;
                    $re = $redis->HINCRBY($redisKey, 'diamond', $diamond);
                    $dbModel->commit();
                    error_log('钻石充值成功' . PHP_EOL, 3, $log);

                    $returnData['status'] = 'ok';
                    $returnData['msg'] = '钻石充值成功';
                }else{
                    $dbModel->rollback();
                    error_log('更新钻石失败' . PHP_EOL, 3, $log);

                    $returnData['status'] = 'faile';
                    $returnData['msg'] = '钻石充值失败';
                }

                $this->ajaxReturn($returnData, 'JSON', 1);
                exit;
                die;
            }
        } catch(Exception $e) {
            error_log('异常' . PHP_EOL, 3, $log);
        } 
    }

    /**
     * 充值德州币更新 log_user_diamond记录表，user_amount 账户表
     */
    public function rechargeCoins()  
    {   
       try {
            $log = ROOT_PATH.'/Public/log/wxpay.log';
            //更新德州余额
            C('DB_PREFIX', '');
            $dbModel = M('user_amount', '',$this->getConnectDb2());
            if ($_POST) { 
                $uid = $_POST['uid'];
                $os =  $_POST['os'];
                $coins =  $_POST['coins'];

                if($dbModel->where(array('uid'=>$uid))->setInc('coins', $coins) != false){
                    //获取德州币余额
                    $userCoins = $dbModel->field('coins')->where(array('uid'=>$uid))->find();
                    if($userCoins === false){
                        $dbModel->rollback();
                        error_log('获取余额失败' . PHP_EOL, 3, $log);
                    } else {

                        $dbModel->commit();
                        error_log('德州币充值成功' . PHP_EOL, 3, $log);

                        $returnData['status'] = 'ok';
                        $returnData['msg'] = '德州币充值成功';
                    }
                }else{
                    $dbModel->rollback();
                    error_log('更新德州币失败' . PHP_EOL, 3, $log);

                    $returnData['status'] = 'faile';
                    $returnData['msg'] = '德州币充值失败';
                }

                $this->ajaxReturn($returnData, 'JSON', 1);
                exit;
                die;
            }
        } catch(Exception $e) {
            error_log('异常' . PHP_EOL, 3, $log);
        } 
    }


    /**
     * 充值钻石更新钻石记录表，账户表钻石
     */
    public function freeze()  
    {   
        try {
            $log = ROOT_PATH.'/Public/log/wxpay.log';
            //更新钻石余额
            C('DB_PREFIX', '');
            $dbModel = M('user', '',$this->getConnectDb2());
            if ($_POST) { 
                $uid = $_POST['uid'];
                $status = $_POST['isblock'] == '0' ? 1 : 0;
                $flag = $_POST['isblock'] == '0' ? '冻结' : '解冻';
                $data['is_block'] =  $status;
                
                if($dbModel->where(array('id'=>$uid))->save($data) != false){
                    $returnData['status'] = 'ok';
                    $returnData['msg'] = '账号'.$flag .'成功';
                }else{
                    $dbModel->rollback();
                    error_log('账号状态更新失败' . PHP_EOL, 3, $log);
                    $returnData['status'] = 'faile';
                    $returnData['msg'] = '账号'.$flag.'失败';
                }

                $this->ajaxReturn($returnData, 'JSON', 1);
                exit;
                die;
            }
        } catch(Exception $e) {
            error_log('异常' . PHP_EOL, 3, $log);
        } 
    }

    public function del()
    {
        $uids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        //uid为1的禁止删除
        if ($uids == 1 or !$uids) {
            $this->error('参数错误！');
        }
        if (is_array($uids)) {
            foreach ($uids as $k => $v) {
                if ($v == 1) {//uid为1的禁止删除
                    unset($uids[$k]);
                }
                $uids[$k] = intval($v);
            }
            if (!$uids) {
                $this->error('参数错误！');
                $uids = implode(',', $uids);
            }
        }

        $map['uid'] = array('in', $uids);
        if (M('member')->where($map)->delete()) {
            M('auth_group_access')->where($map)->delete();
            addlog('删除会员UID：' . $uids);
            $this->success('恭喜，用户删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function edit()
    {

        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        if ($uid) {
            $prefix = C('DB_PREFIX');
            $user = M('member');
            $member = $user->field("{$prefix}member.*,{$prefix}auth_group_access.group_id")->join("{$prefix}auth_group_access ON {$prefix}member.uid = {$prefix}auth_group_access.uid")->where("{$prefix}member.uid=$uid")->find();

        } else {
            $this->error('参数错误！');
        }

        $usergroup = M('auth_group')->field('id,title')->select();
        $this->assign('usergroup', $usergroup);

        $this->assign('member', $member);
        $this->display('form');
    }

    public function update($ajax = '')
    {
        if ($ajax == 'yes') {
            $uid = I('get.uid', 0, 'intval');
            $gid = I('get.gid', 0, 'intval');
            M('auth_group_access')->data(array('group_id' => $gid))->where("uid='$uid'")->save();
            die('1');
        }

        $uid = isset($_POST['uid']) ? intval($_POST['uid']) : false;
        $user = isset($_POST['user']) ? htmlspecialchars($_POST['user'], ENT_QUOTES) : '';
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
        if (!$group_id) {
            $this->error('请选择用户组！');
        }
        $password = isset($_POST['password']) ? trim($_POST['password']) : false;
        if ($password) {
            $data['password'] = password($password);
        }
        $head = I('post.head', '', 'strip_tags');
        $data['sex'] = isset($_POST['sex']) ? intval($_POST['sex']) : 0;
        $data['head'] = $head ? $head : '';
        $data['birthday'] = isset($_POST['birthday']) ? strtotime($_POST['birthday']) : 0;
        $data['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $data['qq'] = isset($_POST['qq']) ? trim($_POST['qq']) : '';
        $data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';


        $data['t'] = time();
        if (!$uid) {
            if ($user == '') {
                $this->error('用户名称不能为空！');
            }
            if (!$password) {
                $this->error('用户密码不能为空！');
            }
            if (M('member')->where("user='$user'")->count()) {
                $this->error('用户名已被占用！');
            }
            $data['user'] = $user;
            $uid = M('member')->data($data)->add();
            M('auth_group_access')->data(array('group_id' => $group_id, 'uid' => $uid))->add();
            addlog('新增会员，会员UID：' . $uid);
        } else {
            M('auth_group_access')->data(array('group_id' => $group_id))->where("uid=$uid")->save();
            addlog('编辑会员信息，会员UID：' . $uid);
            M('member')->data($data)->where("uid=$uid")->save();

        }
        $this->success('操作成功！');
    }


    public function add()
    {
        $usergroup = M('auth_group')->field('id,title')->select();
        $this->assign('usergroup', $usergroup);
        $this->display('form');
    }

    /*
    * 操作redis集合的 CURD
    */
    public function getRedisSets(){
        $redis = $this->getConnectRedis();
        $setList = $redis->keys('*group*');
        $keyNames = I('keyNum') != '' ? I('keyNum') : $setList[0];
        
        $list = $redis->smembers($keyNames); 
        foreach ($list as $key => $value) {
            $list[$key] = array('row' => $key, 'val'=> $value, 'keyNames' => $keyNames);
        }    
        $this->assign('setList', $setList);
        $this->assign('list', $list);
        $this->assign('checked', $keyNames);
        $this->display();
    }

    /*
    * 添加集合
    */
    public function addSet(){
        try {
            $redis = $this->getConnectRedis();
            if ($_POST) { 
                $rowVal = $_POST['rowVal'];
                $setKey = $_POST['setKey'];
               
                if($redis->sadd($setKey, $rowVal)){
                    $returnData['status'] = 'ok';
                    $returnData['msg'] = '集合添加成功';
                }else{
                    $returnData['status'] = 'faile';
                    $returnData['msg'] = '集合添加失败';
                }
                $this->ajaxReturn($returnData, 'JSON', 1);
                exit;
                die;
            }
        } catch(Exception $e) {
            error_log('异常' . PHP_EOL, 3, $log);
        } 
    }

    /*
    * 向指定集合添加row
    */
    public function addSetRow(){
        try {
            $redis = $this->getConnectRedis();
            if ($_POST) { 
                $rowVal = $_POST['rowVal'];
                $setKey = $_POST['setKey'];
               
                if($redis->sadd($setKey, $rowVal)){
                    $returnData['status'] = 'ok';
                    $returnData['msg'] = '添加成功';
                }else{
                    $returnData['status'] = 'faile';
                    $returnData['msg'] = '添加失败';
                }
                $this->ajaxReturn($returnData, 'JSON', 1);
                exit;
                die;
            }
        } catch(Exception $e) {
            error_log('异常' . PHP_EOL, 3, $log);
        } 
    }
    /*
    * 删除指定集合的row
    */
    public function deleteSetRow(){
        try {
            $redis = $this->getConnectRedis();
            if ($_POST) { 
                $rowVal = $_POST['rowVal'];
                $setKey = $_POST['setKey'];
               
                if($redis->sremove($setKey, $rowVal)){
                    $returnData['status'] = 'ok';
                    $returnData['msg'] = '删除成功';
                }else{
                    $returnData['status'] = 'faile';
                    $returnData['msg'] = '删除失败';
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

