<?php
namespace app\controller\admin;

use core\Controller;
use app\model\User as UserModel;

/**
 * 用户管理控制器
 */
class User extends Controller {
    
    /**
     * 用户列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $limit = 15;
        
        $userModel = new UserModel();
        $list = $userModel->order('id', 'DESC')->page($page, $limit)->select();
        $total = $userModel->count();
        
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('totalPage', ceil($total / $limit));
        $this->fetch('user/index');
    }
    
    /**
     * 添加用户
     */
    public function add() {
        if ($this->isMethod('POST')) {
            $data = [
                'username' => $this->post('username'),
                'password' => password_hash($this->post('password'), PASSWORD_BCRYPT),
                'name' => $this->post('name'),
                'email' => $this->post('email'),
                'phone' => $this->post('phone'),
                'status' => $this->post('status', 1),
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ];
            
            $userModel = new UserModel();
            $userModel->insert($data);
            
            $this->successRedirect('添加成功', '/admin/user');
        }
        
        $this->fetch('user/add');
    }
    
    /**
     * 编辑用户
     */
    public function edit() {
        $id = $this->get('id');
        $userModel = new UserModel();
        
        if ($this->isMethod('POST')) {
            $data = [
                'name' => $this->post('name'),
                'email' => $this->post('email'),
                'phone' => $this->post('phone'),
                'status' => $this->post('status', 1),
                'update_time' => date('Y-m-d H:i:s')
            ];
            
            if ($this->post('password')) {
                $data['password'] = password_hash($this->post('password'), PASSWORD_BCRYPT);
            }
            
            $userModel->where('id', $id)->update($data);
            
            $this->successRedirect('更新成功', '/admin/user');
        }
        
        $info = $userModel->where('id', $id)->find();
        $this->assign('info', $info);
        $this->fetch('user/edit');
    }
    
    /**
     * 删除用户
     */
    public function delete() {
        $id = $this->post('id');
        $userModel = new UserModel();
        $userModel->where('id', $id)->delete();
        
        $this->successRedirect('删除成功', '/admin/user');
    }
}
