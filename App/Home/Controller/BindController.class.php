<?php
namespace Home\Controller;
use Think\Controller;
class BindController extends ComController {
    public function index(){
        $stateCode = $this->xmlToArray(); 
        $this->assign('stateCode', $stateCode);
        $this->display();
    }

    public function bind(){
        $openid = session('openid'); //echo $openid;exit;
        $username = I('username');
        $password = I('password'); 
        $ccode = I('ccode');
        $os = I('os');
        $userModel = D('User');
        $userInfo = $userModel->getUserInfo($username, $os);
  
        if( $userInfo && md5(md5( $password ) . $userInfo['salt']) == $userInfo['password'] ){
            //检查是否已被其他微信账号绑定
            $wxBindModel = D('WxBind');
            $result = $wxBindModel->where(array('uid'=>$userInfo['id']))->find();
            if($result){
                echo "<script>alert('该账号已被绑定，请解绑后再试！');</script>";
            }
            $data['openid'] = $openid;
            $data['uid'] = $userInfo['id'];
            $data['os'] = $userInfo['os'];
            $data['bind_time'] = time();
            $data['status'] = 1;
            $data['ccode'] = $ccode;
            $data['username'] = $userInfo['username']; //var_dump($data);exit;
            if( $wxBindModel->add($data) ){
                $this->redirect('Index/index');
               // session('openid', $openid);
            }else{
                $this->error(L('绑定失败，请重试'));
            }
        }else{
            echo "<script>alert('账号或密码错误');</script>";
           // $this->success(L('账号或密码错误'));
        }
    }
}