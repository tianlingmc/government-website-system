<?php
namespace app\controller\admin;

use core\Controller;
use app\model\Judicial as JudicialModel;

/**
 * 裁判文书管理控制器
 */
class Judicial extends Controller {
    
    /**
     * 文书列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $limit = 15;
        
        $judicialModel = new JudicialModel();
        $list = $judicialModel->order('id', 'DESC')->page($page, $limit)->select();
        $total = $judicialModel->count();
        
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('totalPage', ceil($total / $limit));
        $this->fetch('judicial/index');
    }
    
    /**
     * 添加文书
     */
    public function add() {
        if ($this->isMethod('POST')) {
            $data = [
                'case_no' => $this->post('case_no'),
                'case_name' => $this->post('case_name'),
                'court' => $this->post('court'),
                'case_type' => $this->post('case_type'),
                'content' => $this->post('content'),
                'judge_date' => $this->post('judge_date'),
                'status' => $this->post('status', 1),
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ];
            
            $judicialModel = new JudicialModel();
            $judicialModel->insert($data);
            
            $this->successRedirect('添加成功', '/admin/judicial');
        }
        
        $this->fetch('judicial/add');
    }
    
    /**
     * 编辑文书
     */
    public function edit() {
        $id = $this->get('id');
        $judicialModel = new JudicialModel();
        
        if ($this->isMethod('POST')) {
            $data = [
                'case_no' => $this->post('case_no'),
                'case_name' => $this->post('case_name'),
                'court' => $this->post('court'),
                'case_type' => $this->post('case_type'),
                'content' => $this->post('content'),
                'judge_date' => $this->post('judge_date'),
                'status' => $this->post('status', 1),
                'update_time' => date('Y-m-d H:i:s')
            ];
            
            $judicialModel->where('id', $id)->update($data);
            
            $this->successRedirect('更新成功', '/admin/judicial');
        }
        
        $info = $judicialModel->where('id', $id)->find();
        $this->assign('info', $info);
        $this->fetch('judicial/edit');
    }
    
    /**
     * 删除文书
     */
    public function delete() {
        $id = $this->post('id');
        $judicialModel = new JudicialModel();
        $judicialModel->where('id', $id)->delete();
        
        $this->successRedirect('删除成功', '/admin/judicial');
    }
}
