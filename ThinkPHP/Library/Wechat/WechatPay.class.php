<?php
namespace Wechat;

class WechatPay {

    const PREPAY_GATEWAY = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    const QUERY_GATEWAY = 'https://api.mch.weixin.qq.com/pay/orderquery';

    // 配置信息在实例化时传入，以下为范例
    // private $_config = array(
    //     'appid' => 'wxb86f25e68c4b08f0',
    //     'mch_id' => '1381187002',
    //     'key' => '331ea03c552e49da6e75eae5f536d79b',
    //     'secret' => 'f9e2d0e3b688a66d54a55e05dc349a12',
    //     'notify_url' => '',
    // );

    /**
     * 配置
     * @param $config  array 配置信息
     */
    public function __construct($config) {
        $this->_config = $config;
    }

    /**
     * 获取预支付ID
     * @param $body         商品描述
     * @param $out_trade_no 商户订单号
     * @param $total_fee    总金额(单位分)
     * @param $trade_type   交易类型
     */
    public function getPrepayId($body, $out_trade_no, $total_fee, $openid, $trade_type='JSAPI') {
        $data = array();
        $data['appid']        = $this->_config['appid'];
        $data['mch_id']       = $this->_config['mch_id'];
        $data['nonce_str']    = $this->getNonce();
        $data['body']         = $body;
        $data['out_trade_no'] = $out_trade_no;
        $data['total_fee']    = $total_fee;
        $data['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['time_expire'] = date('YmdHis', time() + 3600);//订单失效时间
        $data['notify_url']   = $this->_config['notify_url'];
        $data['trade_type']   = $trade_type;
        $data['openid'] =     $openid;

        $result = $this->post(self::PREPAY_GATEWAY, $data);
        if ($result['return_code'] == 'SUCCESS') {
            return $result['prepay_id'];
        } else {
            // $this->error = $result['return_msg'];
            return null;
        }
    }

    /**
     * 生成H5端调起支付的参数
     */
    public function getPackage($prepayId) {
        $data = array();
        $data['appId'] = $this->_config['appid'];
        $data['package'] = 'prepay_id='.$prepayId;
        $data['nonceStr']  = $this->getNonce();
        $data['timeStamp'] = (string)time();
        $data['signType']   = 'MD5';
        $data['paySign']   = $this->sign($data);
        // $data['appid'] = 'wx2421b1c4370ec43b';
        // $data['package'] = 'prepay_id=u802345jgfjsdfgsdg888';
        // $data['noncestr']  = 'e61463f8efa94090b1f366cccfbbb444';
        // $data['timestamp'] = '1395712654';
        // $data['signType']   = 'MD5';
        // $data['paySign']   = $this->sign($data);
        return $data;
    }

    /**
     * 数组转xml
     */
    public function array2xml($array) {
        $xml = '<xml>' . PHP_EOL;
        foreach ($array as $k => $v) {
            $xml .= "<$k><![CDATA[$v]]></$k>" . PHP_EOL;
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * xml转数组
     */
    public function xml2array($xml) {
        $array = array();
        foreach ((array) simplexml_load_string($xml) as $k => $v) {
            $array[$k] = (string) $v;
        }
        return $array;
    }

    /**
     * 签名
     */
    public function sign($data) {
        ksort($data);
        $string1 = '';
        foreach ($data as $k => $v) {
            if ($v) {
                $string1 .= "$k=$v&";
            }
        }
        $stringSignTemp = $string1 . 'key=' . $this->_config['key'];
        $sign = strtoupper(md5($stringSignTemp));
        return $sign;
    }

    /**
     * 验证是否是腾讯服务器推送数据
     * @param $data 数据数组
     * @return 布尔值
     */
    public function verifyCallback($data) {
        if (!isset($data['sign'])) {
            return false;
        }
        $sign = $data['sign'];
        unset($data['sign']);
        return $this->sign($data) == $sign;
    }

    /**
     * 发送请求
     */
    public function post($url, $data) {
        $data['sign'] = $this->sign($data);
        if (!function_exists('curl_init')) {
            throw new \Exception('Please enable php curl module!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->array2xml($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $content = curl_exec($ch);
        $array = $this->xml2array($content);
        return $array;
    }

    /**
     * 获取随机数
     */
    public function getNonce() {
        return md5(time().mt_rand(100,999));
    }

    /**
     * 获取发送到通知地址的数据(在通知地址内使用)
     * @return 结果数组，如果不是微信服务器发送的数据返回null
     *          appid
     *          bank_type
     *          cash_fee
     *          fee_type
     *          is_subscribe
     *          mch_id
     *          nonce_str
     *          openid
     *          out_trade_no    商户订单号
     *          result_code
     *          return_code
     *          sign
     *          time_end
     *          total_fee       总金额
     *          trade_type
     *          transaction_id  微信支付订单号
     */
    public function getCallback($xml) {
        // $xml = file_get_contents('php://input');
        $data = $this->xml2array($xml);
        if ($this->verifyCallback($data)) {
            return $data;
        } else {
            return null;
        }
    }

    /**
     * 响应微信支付后台通知
     * @param $return_code 返回状态码 SUCCESS/FAIL
     * @param $return_msg  返回信息
     */
    public function responseMsg($return_code='SUCCESS', $return_msg=null) {
        $data = array();
        $data['return_code'] = $return_code;
        if ($return_msg) {
            $data['return_msg'] = $return_msg;
        }
        $xml = $this->array2xml($data);
        print $xml;
    }
}