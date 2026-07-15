<?php
namespace app\controller\home;

use core\Controller;

/**
 * 退出登录控制器
 */
class Logout extends Controller {
    
    /**
     * 退出登录
     */
    public function index() {
        // 清除用户Session
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        
        // 销毁Session
        session_destroy();
        
        // 跳转到首页
        $this->redirect('/');
    }
}
