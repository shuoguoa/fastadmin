<?php
namespace Home\Controller;
use Think\Controller;
use Wechat\WechatPay;

class PayController extends ComController {

    public function callback(){
        
        $wechatpay = new WechatPay(C('WechatPay'));
        $xml = file_get_contents('php://input');
        $log = ROOT_PATH.'/Public/log/wxpay.log';
        error_log($xml . PHP_EOL, 3, $log);
        $result = $wechatpay->getCallback($xml); //var_dump($result);exit;
        if($result){
            if($result['return_code'] != 'SUCCESS' || $result['appid'] != C('WechatPay.appid') || $result['mch_id'] != C('WechatPay.mch_id')){
                error_log('商户信息出错' . PHP_EOL, 3, $log);
                $wechatpay->responseMsg('FAIL');
            }
            //商户订单号
            $out_trade_no = $result['out_trade_no'];
            //微信支付订单号
            $transaction_id = $result['transaction_id'];
            //交易总金额(金额为 分 需要除100)
            $total_fee = $result['total_fee'] / 100;
            //买家支付完成时间
            $time_end = $result['time_end'];

            $wxOrderModel = D('WxOrder');
            $orderInfo = $wxOrderModel->getOrderInfo($out_trade_no);
            if($orderInfo || $orderInfo['status'] != 1){
                if($orderInfo['money'] * $orderInfo['number'] == $total_fee){
                    //update订单
                    $orderData['account_money'] = $total_fee;
                    $orderData['success_time'] = strtotime($time_end);
                    $orderData['status'] = 1;
                    $orderData['extend_order_id'] = $transaction_id;
                    $wxOrderModel->startTrans();
                    try{
                        if($wxOrderModel->where(array('id'=>$orderInfo['id']))->save($orderData)){
                            if ($orderInfo['type'] == 0) { //充值钻石
                                $this->buyDiamond($wxOrderModel, $orderInfo);
                            } else { //购买会员
                                $this->buyVip($wxOrderModel, $orderInfo);
                            }
                        }else{
                            $wxOrderModel->rollback();
                            error_log('更新订单失败' . PHP_EOL, 3, $log);
                            exit($wechatpay->responseMsg('FAIL'));
                        }
                    }catch(\Exception $e){
                        $wxOrderModel->rollback();
                        error_log($e->getMessage() . PHP_EOL, 3, $log);
                        exit($wechatpay->responseMsg('FAIL'));
                    }
                }else{
                    error_log('金额不符' . PHP_EOL, 3, $log);
                }
            }else{
                error_log('订单不存在或已完成' . PHP_EOL, 3, $log);
            }
        }else{
            error_log('验签失败' . PHP_EOL, 3, $log);
            $wechatpay->responseMsg('FAIL');
        }
    }

    /**
     * 处理钻石购买请求
     */
    protected function buyDiamond(&$model, $orderInfo)
    {   
        try {
            $log = '/data/nginx/logs/wxpay/wxpay.log';
            $wechatpay = new WechatPay(C('WechatPay'));
            //更新钻石余额
            $userAmountModel = D('UserAmount');
            if($userAmountModel->where(array('uid'=>$orderInfo['uid']))->setInc('diamond', $orderInfo['diamond'] * $orderInfo['number']) != false){
                //获取钻石余额
                $userDiamond = $userAmountModel->getDiamond($orderInfo['uid']);
                if($userDiamond === false){
                    $model->rollback();
                    error_log('获取余额失败' . PHP_EOL, 3, $log);
                    exit($wechatpay->responseMsg('FAIL'));
                }
                //插入钻石记录表
                $logUserDiamondModel = D('LogUserDiamond');
                if($logUserDiamondModel->addLog($orderInfo['uid'], $orderInfo['diamond'] * $orderInfo['number'], 0, 0, $userDiamond, 11) === false){
                    $model->rollback();
                    error_log('插入log_user_diamond记录表失败' . PHP_EOL, 3, $log);
                    exit($wechatpay->responseMsg('FAIL'));
                }
                //更新redis
                $redis = new \Redis();
                $redis->connect(C('REDIS_HOST'), C('REDIS_PORT'));
                $redis->auth(C('REDIS_AUTH'));
                $redisKey = 'user:' . $orderInfo['uid'];
                $redis->HINCRBY($redisKey, 'diamond', $orderInfo['diamond'] * $orderInfo['number']);
                $model->commit();
                error_log('成功' . PHP_EOL, 3, $log);
                exit($wechatpay->responseMsg('SUCCESS'));
            }else{
                $model->rollback();
                error_log('更新钻石失败' . PHP_EOL, 3, $log);
                exit($wechatpay->responseMsg('FAIL'));
            }
        } catch (Exception $e) {
            throw new Exception("buyvip fail error message: ".$e->getMessage);
        }
        
    }

    /**
     * 处理vip购买请求
     */
    protected function buyVip(&$model, $orderInfo)
    {
        try {
            $log = '/data/nginx/logs/wxpay/wxpay.log';
            $wechatpay = new WechatPay(C('WechatPay'));
            $userModel = D('User');
            $userInfo = $userModel->where(array('id'=>$orderInfo['uid']))->find();
            if (time() > $userInfo['expire_date']) { //如果会员已过期，过期时间设为0，等级为0
                $level = 0;
                $expire_date = 0;
            }else{
                $level = $userInfo['level'];
                $expire_date = $userInfo['expire_date'];
            }
            if ($orderInfo['product_id'] == 3001) {
                if ($level > 0) {
                    $expire_date = $expire_date + (31 * 24 * 60 * 60);
                    $user_data['expire_date'] = $expire_date;
                    if ($expire_date > time() && $level == 2) {
                        $user_data['level'] = 2;
                    } else {
                        if ($expire_date > (time() + (365 * 24 * 60 * 60))) {
                            $user_data['level'] = 2;
                        } else {
                            $user_data['level'] = 1;
                        }
                    }
                } else {
                    $user_data['expire_date'] = time() + 31 * 24 * 60 * 60;
                    $user_data['level'] = 1;
                }
            }
            if ($orderInfo['product_id'] == 3002) {
                if ($level > 0) {
                    $user_data['expire_date'] = $expire_date + (365 * 24 * 60 * 60);
                } else {
                    $user_data['expire_date'] = time() + (365 * 24 * 60 * 60);
                }
                $user_data['level'] = 2;
            }
            $userModel->startTrans();
            if ($userModel->where(array('id'=>$orderInfo['uid']))->save($user_data) === false) {
                error_log('修改用户表会员失败' . PHP_EOL, 3, $log);
                $model->rollback();
                $userModel->rollback();
                exit($wechatpay->responseMsg('FAIL'));
            }
            
            //云信
            $send_data = 'accid=' . $orderInfo['uid'] . '&ex=' . json_encode(['area_id' => $user_info['area_id'], 'level' => $user_data['level'], 'expire_date' => $user_data['expire_date']]);
            $api_action = 'nimserver/user/updateUinfo.action';
            $res = fn_send2netease($api_action, $send_data);
            if ($res['code'] == 200) {
                $model->commit();
                $userModel->commit();
                //更新redis
                $redis = new \Redis();
                $redis->connect(C('REDIS_HOST'), C('REDIS_PORT'));
                $redis->auth(C('REDIS_AUTH'));
                $redisKey = 'user:' . $orderInfo['uid'];
                $redis->hset($redisKey, 'level', $user_data['level']);
                $redis->hset($redisKey, 'expire_date', $user_data['expire_date']);
                exit($wechatpay->responseMsg('SUCCESS'));
            } else {
                error_log('云信失败'. json_encode($res) . PHP_EOL, 3, $log);
                $model->rollback();
                $userModel->rollback();
                exit($wechatpay->responseMsg('FAIL'));
            }
        } catch (Exception $e) {
            throw new Exception("buyvip fail error message: ".$e->getMessage);
        }
    }

    public function order(){
        $goodId = I('id', 0 ,'intval');//商品id
        $openid = session('openid');
        if(C('ENV_DEBUG') && $openid != 'ogxy0w7hrmz4I-RsAaaPT-_emYIA'){//测试账号
            exit(json_encode(array('status'=>0, 'data'=>'维护中')));
        }
        $uid = I('uid', 0 ,'intval');
        if(!$goodId || !$openid || !$uid){
            exit(json_encode(array('status'=>0, 'data'=>'参数错误，请刷新重试')));
        }
        //检查是否当前绑定账号防止误充值
        $wxBindModel = D('wxBind');
        $user = $wxBindModel->getCurrentUser($openid);
        if(!$user || $user['uid'] != $uid){
            exit(json_encode(array('status'=>0, 'data'=>'当前绑定账号错误，请重新选择当前绑定账号')));
        }
        //获取商品信息
        $goodInfo = $this->getGoodsInfo('GOODS_PATH', $user['username'], $goodId);

        //开始生成订单
        $wxOrderModel = D('WxOrder');
        $orderData['uid'] = $uid;
        $orderData['money'] = $goodInfo['price'];
        $orderData['time'] = time();
        $orderData['status'] = 0;
        $orderData['order_id'] = $this->getOrderId($uid);
        $orderData['openid'] = $openid;
        $orderData['product_id'] = $goodId;
        $orderData['diamond'] = $goodInfo['diamond'];
        $orderData['number'] = 1;
        $orderData['date'] = date('Y-m-d', time());
        $orderData['username'] = $user['username'];
        if( $wxOrderModel->add($orderData) ){
            $wechatpay = new WechatPay(C('WechatPay'));
            $prepayId = $wechatpay->getPrepayId($goodInfo['name'], $orderData['order_id'], $orderData['money']*100, $openid);
            $log = ROOT_PATH.'/Public/log/wxpay.log';
        error_log($prepayId . PHP_EOL, 3, $log);
            $data  = $wechatpay->getPackage($prepayId);
        error_log($data . PHP_EOL, 3, $log);
            
            exit(json_encode(array('status'=>1, 'data'=>$data)));
        }
    }

    public function friendOrder(){
        $openid = session('openid');
        if(C('ENV_DEBUG') && $openid != 'ogxy0w7hrmz4I-RsAaaPT-_emYIA'){//测试账号
            exit(json_encode(array('status'=>0, 'data'=>'维护中')));
        }
        $uid = I('uid');
        $os = I('os');
        $goodId = I('good_id');
        
        if(!$openid || !$uid || $os === '' || !$goodId ){
            exit(json_encode(array('status'=>0, 'data'=>'参数错误，请刷新重试')));
        }
        //检查uid跟os是否有误
        $userModel = D('User');
        $user = $userModel->field('id, username')->where(array('id'=>$uid, 'os'=>$os))->find();
        if($user){
            //查看折扣
            $wxBindModel = D('wxBind');
            $current_user = $wxBindModel->getCurrentUser($openid);
            //获取商品信息
            $goodInfo = $this->getGoodsInfo('FRIEND_PATH', $current_user['username'], $goodId);
            //开始生成订单
            $wxOrderModel = D('WxOrder');
            $orderData['uid'] = $uid;
            $orderData['money'] = $goodInfo['price'];//单价
            $orderData['time'] = time();
            $orderData['status'] = 0;
            $orderData['order_id'] = $this->getOrderId($uid);
            $orderData['openid'] = $openid;
            $orderData['product_id'] = $goodId;
            $orderData['date'] = date('Y-m-d', time());
            $orderData['diamond'] = $goodInfo['diamond'];
            //$orderData['number'] = $number;//数量
            $orderData['username'] = $user['username'];
            if( $wxOrderModel->add($orderData) ){
                $wechatpay = new WechatPay(C('WechatPay'));
                $prepayId = $wechatpay->getPrepayId($goodInfo['name'], $orderData['order_id'], $orderData['money']*100, $openid); 
                $data  = $wechatpay->getPackage($prepayId);
                exit(json_encode(array('status'=>1, 'data'=>$data)));
            }
        }else{
            exit(json_encode(array('status'=>0, 'data'=>'充值账号跟系统不符')));
        }
    }

    protected function getGoodsInfo($path, $username = null, $product_id = null){
        /*$file = C($path);
        $xml = simplexml_load_file($file);
        $xml = json_decode(json_encode($xml), true);
        $data = array();
        if($username){
            $wxDiscountModel = D('WxDiscount');
            $discount = $wxDiscountModel->where(array('username'=>$username))->getField('discount');
        }
        foreach ($xml as $key => $value) {
            if($username && $discount && $path != "VIP_PATH"){
                $value['price'] = round($discount * $value['original_price'], 2);
            }else{
                $value['price'] = round($value['discount'] * $value['original_price'], 2);
            }
            $data[$value['id']] = $value;
            unset($value);
        }*/
        return $product_id ? $this->pay($product_id) : $this->pay();
    }

    /**
     * 对交易号进行加密
     * @param int $user_id 用户id
     * @return string
     */
    private function getOrderId($user_id)
    {
        $rand = '';
        for ($i = 0; $i < 6; $i++) {
            $rand .= chr(mt_rand(65, 90));
        }
        $str = "DL_" . $user_id . "_" . substr(md5(time() . $user_id . $rand), 8, 16);
        return $str;
    }

    /**
     * 购买会员
     */
    public function vipOrder()
    {
        $goodId = I('id', 0 ,'intval');//商品id
        $openid = session('openid');
        if(C('ENV_DEBUG') && $openid != 'ogxy0w7hrmz4I-RsAaaPT-_emYIA'){//测试账号
            exit(json_encode(array('status'=>0, 'data'=>'维护中')));
        }
        $uid = I('uid', 0 ,'intval');
        if(!$goodId || !$openid || !$uid){
            exit(json_encode(array('status'=>0, 'data'=>'参数错误，请刷新重试')));
        }
        //检查是否当前绑定账号防止误充值
        $wxBindModel = D('wxBind');
        $user = $wxBindModel->getCurrentUser($openid);
        if(!$user || $user['uid'] != $uid){
            exit(json_encode(array('status'=>0, 'data'=>'当前绑定账号错误，请重新选择当前绑定账号')));
        }
        //获取商品信息
        $goodInfo = $this->getGoodsInfo('VIP_PATH', $user['username'], $goodId);

        //开始生成订单
        $wxOrderModel = D('WxOrder');
        $orderData['uid'] = $uid;
        $orderData['money'] = $goodInfo['price'];
        $orderData['time'] = time();
        $orderData['status'] = 0;
        $orderData['order_id'] = $this->getOrderId($uid);
        $orderData['openid'] = $openid;
        $orderData['product_id'] = $goodId;
        $orderData['number'] = 1;
        $orderData['username'] = $user['username']; 
        $orderData['date'] = date('Y-m-d', time());
        $orderData['type'] = 1; 
        if( $wxOrderModel->add($orderData) ){
            $wechatpay = new WechatPay(C('WechatPay'));
            $prepayId = $wechatpay->getPrepayId($goodInfo['name'], $orderData['order_id'], $orderData['money']*100, $openid);
            $data  = $wechatpay->getPackage($prepayId);
            exit(json_encode(array('status'=>1, 'data'=>$data)));
        }
    }

    /**
     * 为他人购买会员
     */
    public function friendVipOrder()
    {
        $openid = session('openid');
        if(C('ENV_DEBUG') && $openid != 'ogxy0w7hrmz4I-RsAaaPT-_emYIA'){//测试账号
            exit(json_encode(array('status'=>0, 'data'=>'维护中')));
        }
        $uid = I('uid');
        $os = I('os');
        $goodId = I('good_id');
        $number = 1;
        if(!$openid || !$uid || $os === '' || !$goodId || $number <= 0){
            exit(json_encode(array('status'=>0, 'data'=>'参数错误，请刷新重试')));
        }
        //检查uid跟os是否有误
        $userModel = D('User');
        $user = $userModel->field('id, username')->where(array('id'=>$uid, 'os'=>$os))->find();
        if($user){
            //查看折扣
            $wxBindModel = D('wxBind');
            $current_user = $wxBindModel->getCurrentUser($openid);
            //获取商品信息
            $goodInfo = $this->getGoodsInfo('VIP_PATH', $current_user['username'], $goodId);
            //开始生成订单
            $wxOrderModel = D('WxOrder');
            $orderData['uid'] = $uid;
            $orderData['money'] = $goodInfo['price'];//单价
            $orderData['time'] = time();
            $orderData['status'] = 0;
            $orderData['order_id'] = $this->getOrderId($uid);
            $orderData['openid'] = $openid;
            $orderData['product_id'] = $goodId;
            $orderData['number'] = $number;//数量
            $orderData['username'] = $user['username'];
            $orderData['type'] = 1;
            if( $wxOrderModel->add($orderData) ){
                $wechatpay = new WechatPay(C('WechatPay'));
                $prepayId = $wechatpay->getPrepayId($goodInfo['name'].'*'.$number, $orderData['order_id'], $orderData['money']*100*$number, $openid);
                $data  = $wechatpay->getPackage($prepayId);
                exit(json_encode(array('status'=>1, 'data'=>$data)));
            }
        }else{
            exit(json_encode(array('status'=>0, 'data'=>'充值账号跟系统不符')));
        }
    }
}