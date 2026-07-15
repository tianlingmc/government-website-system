<?php
namespace app\controller\admin;

use core\Controller;
use app\model\Admin as AdminModel;

/**
 * 管理员管理控制器
 */
class Admin extends Controller {
    
    /**
     * 管理员列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $limit = 15;
        
        $adminModel = new AdminModel();
        $list = $adminModel->order('id', 'DESC')->page($page, $limit)->select();
        $total = $adminModel->count();
        
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('totalPage', ceil($total / $limit));
        $this->fetch('admin/index');
    }
    
    /**
     * 添加管理员
     */
    public function add() {
        if ($this->isMethod('POST')) {
            $data = [
                'username' => $this->post('username'),
                'password' => password_hash($this->post('password'), PASSWORD_BCRYPT),
                'name' => $this->post('name'),
                'email' => $this->post('email'),
                'role_id' => $this->post('role_id', 1),
                'is_super' => $this->post('is_super', 0),
                'auth_status' => 1,
                'status' => $this->post('status', 1),
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ];
            
            $adminModel = new AdminModel();
            $adminModel->insert($data);
            
            $this->successRedirect('添加成功', '/admin/admin');
        }
        
        $this->fetch('admin/add');
    }
    
    /**
     * 编辑管理员
     */
    public function edit() {
        $id = $this->get('id');
        $adminModel = new AdminModel();
        
        if ($this->isMethod('POST')) {
            $data = [
                'name' => $this->post('name'),
                'email' => $this->post('email'),
                'role_id' => $this->post('role_id', 1),
                'is_super' => $this->post('is_super', 0),
                'status' => $this->post('status', 1),
                'update_time' => date('Y-m-d H:i:s')
            ];
            
            if ($this->post('password')) {
                $data['password'] = password_hash($this->post('password'), PASSWORD_BCRYPT);
            }
            
            $adminModel->where('id', $id)->update($data);
            
            $this->successRedirect('更新成功', '/admin/admin');
        }
        
        $info = $adminModel->where('id', $id)->find();
        $this->assign('info', $info);
        $this->fetch('admin/edit');
    }
    
    /**
     * 删除管理员
     */
    public function delete() {
        $id = $this->post('id');
        
        // 不能删除自己
        if ($id == $_SESSION['admin_id']) {
            $this->errorRedirect('不能删除当前登录账号', '/admin/admin');
        }
        
        $adminModel = new AdminModel();
        $adminModel->where('id', $id)->delete();
        
        $this->successRedirect('删除成功', '/admin/admin');
    }
}
