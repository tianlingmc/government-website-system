<?php
namespace app\controller\admin;

use core\Controller;
use app\model\Admin;

/**
 * 后台登录控制器
 */
class Login extends Controller {
    
    /**
     * 登录页面
     */
    public function index() {
        // 已登录则跳转到后台首页
        if (isset($_SESSION['admin_id'])) {
            $this->redirect('/admin');
        }
        
        $this->fetch('login/index');
    }
    
    /**
     * 登录处理
     */
    public function doLogin() {
        if (!$this->isMethod('POST')) {
            return $this->error('请求方式错误');
        }
        
        $username = $this->post('username');
        $password = $this->post('password');
        
        // 验证参数
        if (empty($username) || empty($password)) {
            return $this->error('用户名和密码不能为空');
        }
        
        // 查询管理员
        $adminModel = new Admin();
        $admin = $adminModel->findByUsername($username);
        
        if (!$admin) {
            return $this->error('用户名或密码错误');
        }
        
        // 检查账号状态
        if ($admin['status'] != 1) {
            return $this->error('账号已被禁用');
        }
        
        // 检查授权状态
        if ($admin['auth_status'] != 1) {
            return $this->error('账号待授权，请联系超级管理员');
        }
        
        // 检查是否被锁定
        if ($adminModel->isLocked($admin['id'])) {
            $lockTime = strtotime($admin['lock_time']);
            $remaining = ceil(($lockTime - time()) / 60);
            return $this->error("账号已锁定，请{$remaining}分钟后重试");
        }
        
        // 验证密码
        if (!$adminModel->verifyPassword($password, $admin['password'])) {
            // 增加失败次数
            $adminModel->increaseFailCount($admin['id']);
            
            $failCount = $admin['login_fail_count'] + 1;
            $remaining = 5 - $failCount;
            
            if ($remaining <= 0) {
                return $this->error('密码错误次数过多，账号已锁定30分钟');
            }
            
            return $this->error("密码错误，还剩{$remaining}次机会");
        }
        
        // 登录成功，更新登录信息
        $adminModel->updateLoginInfo($admin['id'], $_SERVER['REMOTE_ADDR'] ?? '');
        
        // 写入Session
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin'] = [
            'id' => $admin['id'],
            'username' => $admin['username'],
            'name' => $admin['name'],
            'is_super' => $admin['is_super']
        ];
        
        // 记录登录日志
        $this->recordLoginLog($admin['id'], $admin['username'], true);
        
        $this->successRedirect('登录成功', '/admin');
    }
    
    /**
     * 退出登录
     */
    public function logout() {
        // 清除Session
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin']);
        
        session_destroy();
        
        $this->redirect('/admin/login');
    }
    
    /**
     * 验证码
     */
    public function captcha() {
        // 生成验证码
        $width = 120;
        $height = 40;
        $length = 4;
        
        // 生成随机验证码
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        // 保存到session
        $_SESSION['captcha'] = $code;
        $_SESSION['captcha_required'] = true;
        
        // 创建图片
        $image = imagecreatetruecolor($width, $height);
        
        // 背景色
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        imagefill($image, 0, 0, $bgColor);
        
        // 添加干扰线
        for ($i = 0; $i < 5; $i++) {
            $lineColor = imagecolorallocate($image, random_int(100, 200), random_int(100, 200), random_int(100, 200));
            imageline($image, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $lineColor);
        }
        
        // 添加干扰点
        for ($i = 0; $i < 50; $i++) {
            $pixelColor = imagecolorallocate($image, random_int(100, 200), random_int(100, 200), random_int(100, 200));
            imagesetpixel($image, random_int(0, $width), random_int(0, $height), $pixelColor);
        }
        
        // 绘制文字
        $fontColor = imagecolorallocate($image, 50, 50, 50);
        $fontSize = 20;
        $x = 15;
        for ($i = 0; $i < $length; $i++) {
            $y = random_int(25, 32);
            imagechar($image, 5, $x, $y, $code[$i], $fontColor);
            $x += 25;
        }
        
        // 输出图片
        header('Content-Type: image/png');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        imagepng($image);
        imagedestroy($image);
        exit;
    }
    
    /**
     * 记录登录日志
     */
    private function recordLoginLog($userId, $username, $success, $failReason = '') {
        $logData = [
            'user_id' => $userId,
            'user_type' => 1,
            'username' => $username,
            'login_type' => 1,
            'login_status' => $success ? 1 : 0,
            'fail_reason' => $failReason,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];
        
        // 插入日志表
        try {
            $db = $this->app->getDb();
            $prefix = $this->app->getConfig('database.prefix', 'gov_');
            
            $sql = "INSERT INTO {$prefix}login_log (user_id, user_type, username, login_type, login_status, fail_reason, ip_address, user_agent, create_time) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $logData['user_id'],
                $logData['user_type'],
                $logData['username'],
                $logData['login_type'],
                $logData['login_status'],
                $logData['fail_reason'],
                $logData['ip_address'],
                $logData['user_agent']
            ]);
        } catch (\Exception $e) {
            // 日志记录失败不影响登录流程
        }
    }
}
