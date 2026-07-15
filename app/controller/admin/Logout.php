<?php
namespace app\controller\admin;

use core\Controller;

/**
 * 后台退出登录控制器
 */
class Logout extends Controller {
    
    /**
     * 退出登录
     */
    public function index() {
        // 清除管理员Session
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin']);
        
        // 销毁会话
        session_destroy();
        
        // 重定向到登录页面
        $this->redirect('/admin/login');
    }
}
