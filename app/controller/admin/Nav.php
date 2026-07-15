<?php
namespace app\controller\admin;

use core\Controller;

/**
 * 导航管理控制器
 */
class Nav extends Controller {
    
    /**
     * 导航列表
     */
    public function index() {
        // 获取导航列表
        $navs = $this->db()->table('nav')
            ->order('sort', 'asc')
            ->select();
        
        // 构建树形结构
        $tree = $this->buildTree($navs);
        
        $this->assign('navs', $tree);
        $this->display('nav/index');
    }
    
    /**
     * 构建树形结构
     */
    private function buildTree($navs, $parentId = 0) {
        $tree = [];
        foreach ($navs as $nav) {
            if ($nav['parent_id'] == $parentId) {
                $nav['children'] = $this->buildTree($navs, $nav['id']);
                $tree[] = $nav;
            }
        }
        return $tree;
    }
    
    /**
     * 添加导航
     */
    public function add() {
        if (!$this->isMethod('POST')) {
            // 获取上级导航选项
            $parentNavs = $this->db()->table('nav')
                ->where('parent_id', 0)
                ->order('sort', 'asc')
                ->select();
            
            $this->assign('parentNavs', $parentNavs);
            $this->display('nav/add');
            return;
        }
        
        $data = [
            'nav_name' => $this->post('nav_name'),
            'nav_url' => $this->post('nav_url'),
            'nav_type' => $this->post('nav_type', 1),
            'parent_id' => $this->post('parent_id', 0),
            'target' => $this->post('target', '_self'),
            'icon' => $this->post('icon', ''),
            'sort' => $this->post('sort', 0),
            'is_show' => $this->post('is_show', 1)
        ];
        
        // 验证
        if (empty($data['nav_name'])) {
            return $this->error('导航名称不能为空');
        }
        
        $id = $this->db()->table('nav')->insert($data);
        
        if ($id) {
            // 记录日志
            $this->log('create', '添加导航: ' . $data['nav_name']);
            $this->successRedirect('添加成功', '/admin/nav');
        } else {
            $this->errorRedirect('添加失败');
        }
    }
    
    /**
     * 编辑导航
     */
    public function edit() {
        $id = $this->get('id', 0);
        
        $nav = $this->db()->table('nav')->where('id', $id)->find();
        if (!$nav) {
            return $this->error('导航不存在');
        }
        
        if (!$this->isMethod('POST')) {
            // 获取上级导航选项
            $parentNavs = $this->db()->table('nav')
                ->where('parent_id', 0)
                ->where('id', '<>', $id)
                ->order('sort', 'asc')
                ->select();
            
            $this->assign('nav', $nav);
            $this->assign('parentNavs', $parentNavs);
            $this->display('nav/edit');
            return;
        }
        
        $data = [
            'nav_name' => $this->post('nav_name'),
            'nav_url' => $this->post('nav_url'),
            'nav_type' => $this->post('nav_type', 1),
            'parent_id' => $this->post('parent_id', 0),
            'target' => $this->post('target', '_self'),
            'icon' => $this->post('icon', ''),
            'sort' => $this->post('sort', 0),
            'is_show' => $this->post('is_show', 1)
        ];
        
        // 验证
        if (empty($data['nav_name'])) {
            return $this->error('导航名称不能为空');
        }
        
        // 检查是否将导航设置为自己的子导航
        if ($data['parent_id'] == $id) {
            return $this->error('不能将导航设置为自己的子导航');
        }
        
        $result = $this->db()->table('nav')->where('id', $id)->update($data);
        
        if ($result !== false) {
            // 记录日志
            $this->log('update', '编辑导航: ' . $data['nav_name']);
            $this->successRedirect('保存成功', '/admin/nav');
        } else {
            $this->errorRedirect('保存失败');
        }
    }
    
    /**
     * 删除导航
     */
    public function delete() {
        if (!$this->isMethod('POST')) {
            $this->errorRedirect('请求方式错误', '/admin/nav');
        }
        
        $id = $this->post('id', 0);
        
        $nav = $this->db()->table('nav')->where('id', $id)->find();
        if (!$nav) {
            $this->errorRedirect('导航不存在', '/admin/nav');
        }
        
        // 检查是否有子导航
        $childCount = $this->db()->table('nav')->where('parent_id', $id)->count();
        if ($childCount > 0) {
            $this->errorRedirect('请先删除子导航', '/admin/nav');
        }
        
        $result = $this->db()->table('nav')->where('id', $id)->delete();
        
        if ($result) {
            // 记录日志
            $this->log('delete', '删除导航: ' . $nav['nav_name']);
            $this->successRedirect('删除成功', '/admin/nav');
        } else {
            $this->errorRedirect('删除失败', '/admin/nav');
        }
    }
    
    /**
     * 排序导航
     */
    public function sort() {
        if (!$this->isMethod('POST')) {
            $this->errorRedirect('请求方式错误', '/admin/nav');
        }
        
        $ids = $this->post('ids', []);
        
        foreach ($ids as $index => $id) {
            $this->db()->table('nav')->where('id', $id)->update(['sort' => $index]);
        }
        
        $this->successRedirect('排序已保存', '/admin/nav');
    }
}