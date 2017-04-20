<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends ComController {
    
    public function index(){
        $uid = I('post.uid');
        $openid = session('openid');
        $this->assign('userInfo',array('id'=>0));//设置默认值，不然js过不了
        if($openid){ 
            $wxBindModel = D('WxBind'); 
            $user = $wxBindModel->getCurrentUser($openid);
            if($user['uid']){ 
                $userInfo = D('User')->getUserInfoByUid($user['uid']); 
                $userInfo['avatar'] = $userInfo['avatar']=='' ? $this->getDefaultAvatar() : $userInfo['avatar'];
                $this->assign('userInfo',$userInfo);//当前绑定用户id
                $allUserInfo = $wxBindModel->getAllUserInfo($openid);
                foreach ($allUserInfo as $key => $value) {
                    $allUserInfo[$key]['avatar'] = $value['avatar']=='' ? $this->getDefaultAvatar() : $value['avatar'];
                }
                $this->assign('allUserInfo',$allUserInfo);//当前绑定用户id   
            }
        }
        $goods = $this->getGoods();
        $this->assign('goods',$goods);
        $flag = I('get.flag') != '' ? I('get.flag') : 1;
        $this->assign('check', $flag);
        $this->display();
    }

    protected function getGoods(){
        $data = $this->pay();
        return $data;
    }
    public function switchUser(){
        $openid = session('openid');
        $uid = I('uid', 0, 'intval');
        session('uid', $uid);
        if($openid && $uid){
            $wxBindModel = D('WxBind');
            $wxBindModel->startTrans();
            $where['openid'] = $openid;
            $where['uid'] = $uid;
            $result1 = $wxBindModel->where($where)->setField('status',1);
            $where['uid'] = array('neq', $uid);
            $result2 = $wxBindModel->where($where)->setField('status',0);
            if($result1 !== false && $result2 !== false){
                $wxBindModel->commit();
                //$this->success('切换成功', U('Index/index'));
                $this->redirect('Index/index');
            }else{
                $wxBindModel->rollback();
                $this->error('切换失败，请重试！', U('Index/index'));
            }
        }
    }
}