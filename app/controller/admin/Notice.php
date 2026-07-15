<?php
namespace app\controller\admin;

use core\Controller;
use app\model\Notice as NoticeModel;

/**
 * 公告管理控制器
 */
class Notice extends Controller {
    
    /**
     * 公告列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $limit = 15;
        
        $noticeModel = new NoticeModel();
        $list = $noticeModel->order('id', 'DESC')->page($page, $limit)->select();
        $total = $noticeModel->count();
        
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('totalPage', ceil($total / $limit));
        $this->fetch('notice/index');
    }
    
    /**
     * 添加公告
     */
    public function add() {
        if ($this->isMethod('POST')) {
            $data = [
                'title' => $this->post('title'),
                'summary' => $this->post('summary'),
                'content' => $this->post('content'),
                'is_top' => $this->post('is_top', 0),
                'is_important' => $this->post('is_important', 0),
                'status' => $this->post('status', 1),
                'publish_time' => date('Y-m-d H:i:s'),
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ];
            
            $noticeModel = new NoticeModel();
            $noticeModel->insert($data);
            
            $this->successRedirect('添加成功', '/admin/notice');
        }
        
        $this->fetch('notice/add');
    }
    
    /**
     * 编辑公告
     */
    public function edit() {
        $id = $this->get('id');
        $noticeModel = new NoticeModel();
        
        if ($this->isMethod('POST')) {
            $data = [
                'title' => $this->post('title'),
                'summary' => $this->post('summary'),
                'content' => $this->post('content'),
                'is_top' => $this->post('is_top', 0),
                'is_important' => $this->post('is_important', 0),
                'status' => $this->post('status', 1),
                'update_time' => date('Y-m-d H:i:s')
            ];
            
            $noticeModel->where('id', $id)->update($data);
            
            $this->successRedirect('更新成功', '/admin/notice');
        }
        
        $info = $noticeModel->where('id', $id)->find();
        $this->assign('info', $info);
        $this->fetch('notice/edit');
    }
    
    /**
     * 删除公告
     */
    public function delete() {
        $id = $this->post('id');
        $noticeModel = new NoticeModel();
        $noticeModel->where('id', $id)->delete();
        
        $this->successRedirect('删除成功', '/admin/notice');
    }
}
