<?php
namespace app\controller\home;

use core\Controller;
use app\model\User;
use app\model\Settings;

/**
 * 前台登录控制器
 */
class Login extends Controller {
    
    /**
     * 登录页面
     */
    public function index() {
        // 已登录则跳转
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/user/profile');
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
        
        // 查询用户
        $userModel = new User();
        $user = $userModel->findByUsername($username);
        
        if (!$user) {
            return $this->error('用户名或密码错误');
        }
        
        // 检查账号状态
        if ($user['status'] != 1) {
            return $this->error('账号已被禁用');
        }
        
        // 检查是否被锁定
        if ($userModel->isLocked($user['id'])) {
            $lockTime = strtotime($user['lock_time']);
            $remaining = ceil(($lockTime - time()) / 60);
            return $this->error("账号已锁定，请{$remaining}分钟后重试");
        }
        
        // 验证密码
        if (!$userModel->verifyPassword($password, $user['password'])) {
            // 增加失败次数
            $userModel->increaseFailCount($user['id']);
            
            $failCount = $user['login_fail_count'] + 1;
            $remaining = 5 - $failCount;
            
            if ($remaining <= 0) {
                return $this->error('密码错误次数过多，账号已锁定30分钟');
            }
            
            return $this->error("密码错误，还剩{$remaining}次机会");
        }
        
        // 登录成功
        $userModel->updateLoginInfo($user['id'], $_SERVER['REMOTE_ADDR'] ?? '');
        
        // 写入Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
        
        $this->successRedirect('登录成功', '/user/profile');
    }
    
    /**
     * 注册页面
     */
    public function register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/user/profile');
        }
        
        $this->fetch('login/register');
    }
    
    /**
     * 注册处理
     */
    public function doRegister() {
        if (!$this->isMethod('POST')) {
            return $this->error('请求方式错误');
        }
        
        $username = $this->post('username');
        $password = $this->post('password');
        $confirmPassword = $this->post('confirm_password');
        $realName = $this->post('real_name');
        $phone = $this->post('phone');
        $email = $this->post('email');
        
        // 验证
        if (empty($username) || empty($password)) {
            return $this->error('用户名和密码不能为空');
        }
        
        if (strlen($username) < 3 || strlen($username) > 20) {
            return $this->error('用户名长度为3-20个字符');
        }
        
        if ($password !== $confirmPassword) {
            return $this->error('两次输入的密码不一致');
        }
        
        if (strlen($password) < 6) {
            return $this->error('密码长度至少6位');
        }
        
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error('邮箱格式不正确');
        }
        
        $userModel = new User();
        
        // 检查用户名是否已存在
        if ($userModel->findByUsername($username)) {
            return $this->error('用户名已存在');
        }
        
        // 检查邮箱是否已存在
        if ($userModel->findByEmail($email)) {
            return $this->error('邮箱已被注册');
        }
        
        // 创建用户
        $userId = $userModel->insert([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'real_name' => $realName,
            'phone' => $phone,
            'email' => $email,
            'status' => 1,
            'create_time' => date('Y-m-d H:i:s')
        ]);
        
        if ($userId) {
            $this->successRedirect('注册成功，请登录', '/login');
        }
        
        $this->errorRedirect('注册失败，请重试');
    }
    
    /**
     * 生成验证码
     */
    public function captcha() {
        // 生成随机验证码
        $code = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4);
        $_SESSION['captcha'] = $code;
        
        // 创建图片
        $width = 120;
        $height = 40;
        $image = imagecreatetruecolor($width, $height);
        
        // 背景色
        $bgColor = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgColor);
        
        // 文字颜色
        $textColor = imagecolorallocate($image, 50, 50, 50);
        
        // 添加干扰线
        for ($i = 0; $i < 3; $i++) {
            $lineColor = imagecolorallocate($image, rand(150, 200), rand(150, 200), rand(150, 200));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }
        
        // 添加文字
        $font = 5;
        $x = 20;
        for ($i = 0; $i < strlen($code); $i++) {
            imagestring($image, $font, $x, 10, $code[$i], $textColor);
            $x += 25;
        }
        
        // 输出图片
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
        exit;
    }
}
