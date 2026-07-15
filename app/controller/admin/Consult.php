<?php
namespace app\controller\admin;

use core\Controller;
use app\model\Consult as ConsultModel;

/**
 * 咨询管理控制器
 */
class Consult extends Controller {
    
    /**
     * 咨询列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $limit = 15;
        $status = $this->get('status', '');
        
        $consultModel = new ConsultModel();
        
        if ($status !== '') {
            $consultModel->where('status', $status);
        }
        
        $list = $consultModel->order('id', 'DESC')->page($page, $limit)->select();
        $total = $consultModel->count();
        
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('totalPage', ceil($total / $limit));
        $this->assign('status', $status);
        $this->fetch('consult/index');
    }
    
    /**
     * 查看咨询详情
     */
    public function view() {
        $id = $this->get('id');
        $consultModel = new ConsultModel();
        
        $info = $consultModel->where('id', $id)->find();
        
        // 标记为已读
        if ($info && $info['status'] == 0) {
            $consultModel->where('id', $id)->update(['status' => 1, 'update_time' => date('Y-m-d H:i:s')]);
        }
        
        $this->assign('info', $info);
        $this->fetch('consult/view');
    }
    
    /**
     * 回复咨询
     */
    public function reply() {
        $id = $this->post('id');
        $reply = $this->post('reply');
        
        $consultModel = new ConsultModel();
        $consultModel->where('id', $id)->update([
            'reply_content' => $reply,
            'reply_time' => date('Y-m-d H:i:s'),
            'status' => 2,
            'update_time' => date('Y-m-d H:i:s')
        ]);
        
        $this->successRedirect('回复成功', '/admin/consult');
    }
    
    /**
     * 删除咨询
     */
    public function delete() {
        $id = $this->post('id');
        $consultModel = new ConsultModel();
        $consultModel->where('id', $id)->delete();
        
        $this->successRedirect('删除成功', '/admin/consult');
    }
}
