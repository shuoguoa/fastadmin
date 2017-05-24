<?php
namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
class BannerController extends ComController {
    public function index(){
        $where = array();
        C('DB_PREFIX', '');
        $pagesize = 15;
        $p = intval($_GET['p']) > 0 ? $_GET['p'] : 1;
        $first = $pagesize * ($p - 1);
        $dbModel = M('banner_image');
        $list = $dbModel->where($where)->limit($first . ',' . $pagesize)->order('id desc')->select();

        $count = $dbModel->where($where)->count('*');
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
    
    public function uploadpic(){
        $this->display();
    }

    public function upload(){
        $url = I('url');
        $sort = I('sort');
        
        C('DB_PREFIX', '');
        $img = I('image') ? getImage(I('image')) : '';
        $data = array(
            'img' => $img,
            'url' => $url,
            'sort' => $sort
        );
        
        if ($img != '' && $url != '' && $sort != '') {
            $dbModel = M('banner_image');
            $dbModel->startTrans();
            if ($dbModel->add($data)){
                if($dbModel->commit()){
                    echo "<script>alert('上传成功');history.back();</script>";    
                }
            } else {
                $dbModel->rollback();
            } 
        }
    }

    public function update()
    {
        try {
            //更新sort值
            C('DB_PREFIX', '');
            $dbModel = M('banner_image');
            if ($_POST) { 
                $id = $_POST['id'];
                $sort =  $_POST['sort'];
                $data = array('sort' => $sort );
                $result = $dbModel->where(array('id'=>$id))->save($data); 
                if($result){
                    $returnData['status'] = 'ok';
                    $returnData['msg'] = '更新成功';
                }else{
                    $returnData['status'] = 'faile';
                    $returnData['msg'] = 'sort值更新失败';
                }
                $this->ajaxReturn($returnData, 'JSON', 1);
                exit;
                die;
            }
        } catch(Exception $e) {
            error_log('异常' . PHP_EOL, 3, $log);
        } 
    }
    
    public function delete()
    {
        try {
            //更新sort值
            C('DB_PREFIX', '');
            $dbModel = M('banner_image');
            if ($_POST) { 
                $id = $_POST['id'];
                $img = $_POST['img'];
                $result = $dbModel->where(array('id'=>$id))->delete();
                //删除指定的路径的图片
                $deleteImg = unlink($img); 

                if( $result != false){
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