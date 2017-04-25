<?php
namespace Home\Controller;
use Think\Controller;
class ComController extends Controller {

    public function _initialize(){
        /**/
        $param = I('test');
        $appid = C('WechatPay.appid');
        $secret = C('WechatPay.secret');

        $code = I('get.code',0);
        $openid = session('openid');
        //没有openid就进行获取操作
        if(!$openid){
            if(!$code){
                $this->getCode();
            }

            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&$test='.$param.'&code='.$code.'&grant_type=authorization_code';
//            $get_token_url0 = 'http://liufuqin.test.htgames.cn/qwadmin/index.php?m=Home&c=Bind&a=index';
//             $param = I('test');
//            if ($param) {
//                $data = $this->httpGet($get_token_url0);
//            } else {
//                $data = $this->httpGet($get_token_url);
//            }
//
            $data = $this->httpGet($get_token_url);
            $data = json_decode($data, true); 
            session('openid',$data['openid']);

        } 
    
        //过滤首页和绑定页
        $page = CONTROLLER_NAME.'/'.ACTION_NAME;
        if($page == 'Pay/order' || $page == 'Order/index'){
            //判断是否已经绑定
            $wxBindModel = D('WxBind');
            $openid = session('openid');
            $data = $wxBindModel->where(array('openid'=>$openid))->select();
            if(!$data){
                $this->redirect('Bind/index');
            }
        }

    }

    private function httpGet($url) {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);//不处理header
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);//超时时间
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//关闭直接输出
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//不验证ssl
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/cacert.pem');
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public function getDefaultAvatar(){
        return 'qwadmin/Public/home/images/head.png';
    }

    private function getCode(){
        $appid = C('WechatPay.appid');
//        $secret = C('WechatPay.secret');
//        $code = I('get.id',0);
        $url = "http://liufuqin.test.htgames.cn";
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($url).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        header("Location:".$url);
        die();
    }

                                
    //将地区xml转化成数组
    function xmlToArray()
    {
        //读取xml 地区列表
        $s=join("",file(ROOT_PATH.'/Public/arrays_country_code.xml')); 
        $arr = $this->xml_to_array($s);
        $key = array_keys($arr);
        return $arr[$key[0]]['item'];
    }

    function xml_to_array( $xml )
    {
        $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches))
        {
            $count = count($matches[0]);
            $arr = array();
            for($i = 0; $i < $count; $i++)
            {
                $key= $matches[1][$i];
                $val = $this->xml_to_array( $matches[2][$i] );  // 递归
                if(array_key_exists($key, $arr))
                {
                    if(is_array($arr[$key]))
                    {
                        if(!array_key_exists(0,$arr[$key]))
                        {
                            $arr[$key] = array($arr[$key]);
                        }
                    }else{
                        $arr[$key] = array($arr[$key]);
                    }
                    $arr[$key][] = $val;
                }else{
                    $arr[$key] = $val;
                }
            }
            return $arr;
        }else{
            return $xml;
        }
    }
     

     /**
     * 充值
     *
     * @return void
     * @author
     **/
    function pay($id = 0)
    {
        $bid = $this->get('bid');
        if ($bid) {
            $pay = [
                2001 => ['id' => 2001,
                    'ios_id'=> $bid.'.rmb6',
                    'name' => '6',
                    'diamond' => 60,
                    'vip' => 0,
                    'desc' => '含钻石60颗',
                    'price' => 6
                ],
                2002 => ['id' => 2002,
                    'ios_id'=> $bid.'.rmb30',
                    'name' => '30',
                    'diamond' => 318,
                    'vip' => 0,
                    'desc' => '含钻石318颗',
                    'price' => 30
                ],
                2003 => ['id' => 2003,
                    'ios_id'=> $bid.'.rmb128',
                    'name' => '128',
                    'diamond' => 1388,
                    'vip' => 0,
                    'desc' => '含钻石1388颗',
                    'price' => 128
                ],
                2004 => ['id' => 2004,
                    'ios_id'=> $bid.'.rmb328',
                    'name' => '328',
                    'diamond' => 3608,
                    'vip' => 0,
                    'desc' => '含钻石3608颗',
                    'price' => 328
                ],
                2005 => ['id' => 2005,
                    'ios_id'=> $bid.'.rmb618',
                    'name' => '618',
                    'diamond' => 6888,
                    'vip' => 0,
                    'desc' => '含钻石6888颗',
                    'price' => 618
                ],
                2006 => ['id' => 2006,
                    'ios_id'=> $bid.'.rmb999',
                    'name' => '999',
                    'diamond' => 11188,
                    'vip' => 0,
                    'desc' => '含钻石6888颗',
                    'price' => 999
                ],
            ];
        }else{
            $pay = [
               2000 => ['id' => 2000,
                    'ios_id'=> 'com.htgames.nutspoker.rmb6',
                    'name' => '0.01',
                    'diamond' => 6,
                    'vip' => 0,
                    'desc' => '含钻石1颗',
                    'price' => 0.01
                ],
               /*  2001 => ['id' => 2001,
                    'ios_id'=> 'com.htgames.nutspoker.rmb6',
                    'name' => '6',
                    'diamond' => 60,
                    'vip' => 0,
                    'desc' => '含钻石60颗',
                    'price' => 6
                ],*/
                2002 => ['id' => 2002,
                    'ios_id'=> 'com.htgames.nutspoker.rmb30',
                    'name' => '30',
                    'diamond' => 318,
                    'vip' => 0,
                    'desc' => '含钻石318颗',
                    'price' => 30
                ],
                2003 => ['id' => 2003,
                    'ios_id'=> 'com.htgames.nutspoker.rmb128',
                    'name' => '128',
                    'diamond' => 1388,
                    'vip' => 0,
                    'desc' => '含钻石1388颗',
                    'price' => 128
                ],
                2004 => ['id' => 2004,
                    'ios_id'=> 'com.htgames.nutspoker.rmb328',
                    'name' => '328',
                    'diamond' => 3608,
                    'vip' => 0,
                    'desc' => '含钻石3608颗',
                    'price' => 328
                ],
                2005 => ['id' => 2005,
                    'ios_id'=> 'com.htgames.nutspoker.rmb618',
                    'name' => '618',
                    'diamond' => 6888,
                    'vip' => 0,
                    'desc' => '含钻石6888颗',
                    'price' => 618
                ],
                2006 => ['id' => 2006,
                    'ios_id'=> $bid.'.rmb999',
                    'name' => '999',
                    'diamond' => 11188,
                    'vip' => 0,
                    'desc' => '含钻石6888颗',
                    'price' => 999
                ],
            ];
        }
        if ($id) {
            return isset($pay[$id]) ? $pay[$id] : '';
        } else {
            return array_values($pay);
        }
    }

}

