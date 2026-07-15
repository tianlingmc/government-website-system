<?php
namespace app\controller\admin;

use core\Controller;

/**
 * 角色管理控制器
 */
class Role extends Controller {
    
    /**
     * 角色列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $pageSize = $this->get('pageSize', 20);
        $keyword = $this->get('keyword', '');
        
        $where = [];
        if (!empty($keyword)) {
            $where['name'] = ['like', '%' . $keyword . '%'];
        }
        
        $roles = $this->db()->table('admin_role')
            ->where($where)
            ->order('id', 'asc')
            ->page($page, $pageSize)
            ->select();
        
        $total = $this->db()->table('admin_role')->where($where)->count();
        
        // 获取每个角色的管理员数量
        foreach ($roles as &$role) {
            $role['admin_count'] = $this->db()->table('admin')
                ->where('role_id', $role['id'])
                ->count();
        }
        
        $this->assign('roles', $roles);
        $this->assign('total', $total);
        $this->assign('page', $page);
        $this->assign('pageSize', $pageSize);
        $this->assign('keyword', $keyword);
        $this->display('role/index');
    }
    
    /**
     * 添加角色
     */
    public function add() {
        if ($this->isMethod('POST')) {
            $data = [
                'name' => $this->post('name'),
                'description' => $this->post('description'),
                'permissions' => json_encode($this->post('permissions', [])),
                'status' => $this->post('status', 1),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            if (empty($data['name'])) {
                return $this->error('角色名称不能为空');
            }
            
            // 检查角色名是否已存在
            $exists = $this->db()->table('admin_role')
                ->where('name', $data['name'])
                ->find();
            if ($exists) {
                return $this->error('角色名称已存在');
            }
            
            $id = $this->db()->table('admin_role')->insert($data);
            if ($id) {
                $this->log('create', '添加角色: ' . $data['name']);
                $this->successRedirect('添加成功', '/admin/role');
            }
            $this->errorRedirect('添加失败');
        }
        
        // 获取所有权限列表
        $permissions = $this->getAllPermissions();
        $this->assign('permissions', $permissions);
        $this->assign('rolePermissions', []);
        $this->display('role/add');
    }
    
    /**
     * 编辑角色
     */
    public function edit() {
        $id = $this->get('id', 0);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        
        $role = $this->db()->table('admin_role')->where('id', $id)->find();
        if (!$role) {
            return $this->error('角色不存在');
        }
        
        if ($this->isMethod('POST')) {
            $data = [
                'name' => $this->post('name'),
                'description' => $this->post('description'),
                'permissions' => json_encode($this->post('permissions', [])),
                'status' => $this->post('status', 1),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if (empty($data['name'])) {
                return $this->error('角色名称不能为空');
            }
            
            // 检查角色名是否已存在（排除自己）
            $exists = $this->db()->table('admin_role')
                ->where('name', $data['name'])
                ->where('id', '!=', $id)
                ->find();
            if ($exists) {
                return $this->error('角色名称已存在');
            }
            
            $result = $this->db()->table('admin_role')->where('id', $id)->update($data);
            if ($result !== false) {
                $this->log('update', '编辑角色: ' . $data['name']);
                $this->successRedirect('更新成功', '/admin/role');
            }
            $this->errorRedirect('更新失败');
        }
        
        // 解析权限
        $role['permissions'] = json_decode($role['permissions'] ?? '[]', true);
        
        // 获取所有权限列表
        $permissions = $this->getAllPermissions();
        
        $this->assign('role', $role);
        $this->assign('permissions', $permissions);
        $this->assign('rolePermissions', $role['permissions']);
        $this->display('role/edit');
    }
    
    /**
     * 删除角色
     */
    public function delete() {
        if (!$this->isMethod('POST')) {
            $this->errorRedirect('请求方式错误', '/admin/role');
        }
        
        $id = $this->post('id', 0);
        if (empty($id)) {
            $this->errorRedirect('参数错误', '/admin/role');
        }
        
        // 检查是否有管理员使用此角色
        $adminCount = $this->db()->table('admin')
            ->where('role_id', $id)
            ->count();
        if ($adminCount > 0) {
            $this->errorRedirect('该角色下有管理员，无法删除', '/admin/role');
        }
        
        $role = $this->db()->table('admin_role')->where('id', $id)->find();
        if (!$role) {
            $this->errorRedirect('角色不存在', '/admin/role');
        }
        
        $result = $this->db()->table('admin_role')->where('id', $id)->delete();
        if ($result) {
            $this->log('delete', '删除角色: ' . $role['role_name']);
            $this->successRedirect('删除成功', '/admin/role');
        }
        $this->errorRedirect('删除失败', '/admin/role');
    }
    
    /**
     * 获取所有权限列表
     */
    private function getAllPermissions() {
        return [
            'dashboard' => [
                'name' => '控制台',
                'permissions' => [
                    'dashboard.view' => '查看控制台'
                ]
            ],
            'content' => [
                'name' => '内容管理',
                'permissions' => [
                    'notice.manage' => '公告管理',
                    'policy.manage' => '政策法规管理',
                    'judicial.manage' => '裁判文书管理'
                ]
            ],
            'user' => [
                'name' => '用户管理',
                'permissions' => [
                    'user.manage' => '用户管理',
                    'consult.manage' => '咨询管理'
                ]
            ],
            'system' => [
                'name' => '系统管理',
                'permissions' => [
                    'admin.manage' => '管理员管理',
                    'role.manage' => '角色管理',
                    'media.manage' => '媒体库管理',
                    'settings.manage' => '系统设置',
                    'log.view' => '查看日志',
                    'backup.manage' => '数据备份',
                    'nav.manage' => '导航管理',
                    'language.manage' => '文字配置'
                ]
            ]
        ];
    }
}
