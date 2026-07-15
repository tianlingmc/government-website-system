<?php
namespace app\model;

use core\Model;

/**
 * 管理员模型
 */
class Admin extends Model {
    
    protected $table = 'admin';
    
    /**
     * 根据用户名查找
     */
    public function findByUsername($username) {
        return $this->where('username', $username)->find();
    }
    
    /**
     * 根据邮箱查找
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
     * 创建管理员
     */
    public function createAdmin($data) {
        // 密码加密
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        return $this->insert($data);
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
        $admin = $this->find($id);
        if ($admin) {
            $failCount = $admin['login_fail_count'] + 1;
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
        $admin = $this->find($id);
        if (!$admin || empty($admin['lock_time'])) {
            return false;
        }
        
        return strtotime($admin['lock_time']) > time();
    }
    
    /**
     * 授权账号
     */
    public function authorize($id, $expireTime = null) {
        $data = [
            'auth_status' => 1,
            'auth_time' => date('Y-m-d H:i:s')
        ];
        
        if ($expireTime) {
            $data['auth_expire_time'] = $expireTime;
        }
        
        return $this->where('id', $id)->update($data);
    }
    
    /**
     * 撤销授权
     */
    public function revokeAuth($id) {
        return $this->where('id', $id)->update([
            'auth_status' => 0,
            'auth_time' => null,
            'auth_expire_time' => null
        ]);
    }
    
    /**
     * 获取角色权限
     */
    public function getPermissions($roleId) {
        $db = $this->app->getDb();
        $prefix = $this->app->getConfig('database.prefix', 'gov_');
        
        $sql = "SELECT permissions FROM {$prefix}admin_role WHERE id = ? AND status = 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$roleId]);
        
        $result = $stmt->fetch();
        if ($result && $result['permissions']) {
            return json_decode($result['permissions'], true);
        }
        
        return [];
    }
    
    /**
     * 检查权限
     */
    public function hasPermission($adminId, $permission) {
        $admin = $this->find($adminId);
        if (!$admin) {
            return false;
        }
        
        // 超级管理员拥有所有权限
        if ($admin['is_super']) {
            return true;
        }
        
        $permissions = $this->getPermissions($admin['role_id']);
        
        // 检查通配符权限
        if (in_array('*', $permissions)) {
            return true;
        }
        
        // 检查具体权限
        if (in_array($permission, $permissions)) {
            return true;
        }
        
        // 检查通配符匹配
        foreach ($permissions as $p) {
            if (substr($p, -2) === '.*') {
                $prefix = substr($p, 0, -2);
                if (strpos($permission, $prefix . '.') === 0) {
                    return true;
                }
            }
        }
        
        return false;
    }
}
