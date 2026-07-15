<?php
namespace app\controller\admin;

use core\Controller;

/**
 * 文字配置控制器
 */
class Language extends Controller {
    
    /**
     * 配置列表
     */
    public function index() {
        $group = $this->get('group', 'common');
        
        // 获取配置分组
        $groups = [
            'common' => '通用',
            'nav' => '导航',
            'user' => '用户相关',
            'judicial' => '裁判文书'
        ];
        
        // 获取配置列表
        $configs = $this->db()->table('language')
            ->where('lang_group', $group)
            ->order('id', 'asc')
            ->select();
        
        $this->assign('configs', $configs);
        $this->assign('group', $group);
        $this->assign('groups', $groups);
        $this->display('language/index');
    }
    
    /**
     * 保存配置
     */
    public function save() {
        if (!$this->isMethod('POST')) {
            return $this->error('请求方式错误');
        }
        
        $configs = $this->post('configs', []);
        
        foreach ($configs as $key => $value) {
            $this->db()->table('language')
                ->where('lang_key', $key)
                ->update(['lang_value' => $value, 'update_time' => date('Y-m-d H:i:s')]);
        }
        
        // 记录日志
        $this->log('update', '更新文字配置');
        
        $this->successRedirect('保存成功', '/admin/language');
    }
    
    /**
     * 添加配置项
     */
    public function add() {
        if (!$this->isMethod('POST')) {
            $groups = [
                'common' => '通用',
                'nav' => '导航',
                'user' => '用户相关',
                'judicial' => '裁判文书'
            ];
            
            $modules = [
                'common' => '通用模块',
                'judicial' => '裁判文书模块'
            ];
            
            $this->assign('groups', $groups);
            $this->assign('modules', $modules);
            $this->display('language/add');
            return;
        }
        
        $data = [
            'lang_key' => $this->post('lang_key'),
            'lang_value' => $this->post('lang_value', ''),
            'lang_group' => $this->post('lang_group', 'common'),
            'module' => $this->post('module', 'common'),
            'is_default' => $this->post('is_default', 0),
            'create_time' => date('Y-m-d H:i:s')
        ];
        
        // 验证
        if (empty($data['lang_key'])) {
            return $this->error('语言键不能为空');
        }
        
        // 检查key是否已存在
        $exists = $this->db()->table('language')->where('lang_key', $data['lang_key'])->find();
        if ($exists) {
            return $this->error('语言键已存在');
        }
        
        $id = $this->db()->table('language')->insert($data);
        
        if ($id) {
            // 记录日志
            $this->log('create', '添加语言配置: ' . $data['lang_key']);
            $this->successRedirect('添加成功', '/admin/language?group=' . $data['lang_group']);
        } else {
            $this->errorRedirect('添加失败');
        }
    }
    
    /**
     * 编辑配置项
     */
    public function edit() {
        $id = $this->get('id', 0);
        
        $config = $this->db()->table('language')->where('id', $id)->find();
        if (!$config) {
            return $this->error('配置项不存在');
        }
        
        if (!$this->isMethod('POST')) {
            $groups = [
                'common' => '通用',
                'nav' => '导航',
                'user' => '用户相关',
                'judicial' => '裁判文书'
            ];
            
            $modules = [
                'common' => '通用模块',
                'judicial' => '裁判文书模块'
            ];
            
            $this->assign('config', $config);
            $this->assign('groups', $groups);
            $this->assign('modules', $modules);
            $this->display('language/edit');
            return;
        }
        
        $data = [
            'lang_value' => $this->post('lang_value', ''),
            'lang_group' => $this->post('lang_group'),
            'module' => $this->post('module', 'common'),
            'is_default' => $this->post('is_default', 0),
            'update_time' => date('Y-m-d H:i:s')
        ];
        
        if (empty($data['lang_value'])) {
            return $this->error('语言值不能为空');
        }
        
        $result = $this->db()->table('language')->where('id', $id)->update($data);
        
        if ($result !== false) {
            // 记录日志
            $this->log('update', '编辑语言配置: ' . $config['lang_key']);
            $this->successRedirect('保存成功', '/admin/language?group=' . $data['lang_group']);
        } else {
            $this->errorRedirect('保存失败');
        }
    }
    
    /**
     * 删除配置项
     */
    public function delete() {
        if (!$this->isMethod('POST')) {
            $this->errorRedirect('请求方式错误', '/admin/language');
        }
        
        $id = $this->post('id', 0);
        
        $config = $this->db()->table('language')->where('id', $id)->find();
        if (!$config) {
            $this->errorRedirect('配置项不存在', '/admin/language');
        }
        
        $result = $this->db()->table('language')->where('id', $id)->delete();
        
        if ($result) {
            // 记录日志
            $this->log('delete', '删除语言配置: ' . $config['lang_key']);
            $this->successRedirect('删除成功', '/admin/language');
        } else {
            $this->errorRedirect('删除失败', '/admin/language');
        }
    }
}
