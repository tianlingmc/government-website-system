<?php
namespace app\model;

use core\Model;

/**
 * 用户模型
 */
class User extends Model {
    
    protected $table = 'user';
    
    /**
     * 根据用户名查找用户
     */
    public function findByUsername($username) {
        return $this->where('username', $username)->find();
    }
    
    /**
     * 根据邮箱查找用户
     */
    public function findByEmail($email) {
        return $this->where('email', $email)->find();
    }
    
    /**
     * 验证密码
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * 更新登录信息
     */
    public function updateLoginInfo($id, $ip) {
        return $this->where('id', $id)->update([
            'last_login_time' => date('Y-m-d H:i:s'),
            'last_login_ip' => $ip,
            'login_fail_count' => 0,
            'lock_time' => null
        ]);
    }
    
    /**
     * 增加登录失败次数
     */
    public function increaseFailCount($id) {
        $user = $this->find($id);
        if ($user) {
            $failCount = $user['login_fail_count'] + 1;
            $data = ['login_fail_count' => $failCount];
            
            // 超过5次锁定30分钟
            if ($failCount >= 5) {
                $data['lock_time'] = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            }
            
            return $this->where('id', $id)->update($data);
        }
        return false;
    }
    
    /**
     * 检查账号是否被锁定
     */
    public function isLocked($id) {
        $user = $this->find($id);
        if (!$user || empty($user['lock_time'])) {
            return false;
        }
        
        return strtotime($user['lock_time']) > time();
    }
}
