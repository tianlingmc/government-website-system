<?php
namespace app\controller\admin;

use core\Controller;

/**
 * 操作日志控制器
 */
class Log extends Controller {
    
    /**
     * 日志列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $pageSize = $this->get('pageSize', 20);
        $keyword = $this->get('keyword', '');
        $type = $this->get('type', '');
        $adminId = $this->get('admin_id', 0);
        $dateStart = $this->get('date_start', '');
        $dateEnd = $this->get('date_end', '');
        
        $where = [];
        if (!empty($keyword)) {
            $where['content'] = ['like', '%' . $keyword . '%'];
        }
        if (!empty($type)) {
            $where['type'] = $type;
        }
        if ($adminId > 0) {
            $where['admin_id'] = $adminId;
        }
        if (!empty($dateStart)) {
            $where['created_at'] = ['>=', $dateStart . ' 00:00:00'];
        }
        if (!empty($dateEnd)) {
            $where['created_at'] = ['<=', $dateEnd . ' 23:59:59'];
        }
        
        // 获取日志列表
        $logs = $this->db()->table('operation_log')
            ->where($where)
            ->order('id', 'desc')
            ->page($page, $pageSize)
            ->select();
        
        // 获取总数
        $total = $this->db()->table('operation_log')->where($where)->count();
        
        // 获取管理员列表
        $admins = $this->db()->table('admin')->field('id, username')->select();
        $adminMap = array_column($admins, 'username', 'id');
        
        // 日志类型
        $types = [
            'login' => '登录',
            'logout' => '退出',
            'create' => '新增',
            'update' => '修改',
            'delete' => '删除',
            'upload' => '上传',
            'download' => '下载',
            'system' => '系统'
        ];
        
        $this->assign('logs', $logs);
        $this->assign('total', $total);
        $this->assign('page', $page);
        $this->assign('pageSize', $pageSize);
        $this->assign('keyword', $keyword);
        $this->assign('type', $type);
        $this->assign('adminId', $adminId);
        $this->assign('dateStart', $dateStart);
        $this->assign('dateEnd', $dateEnd);
        $this->assign('admins', $adminMap);
        $this->assign('types', $types);
        $this->display('log/index');
    }
    
    /**
     * 查看日志详情
     */
    public function view() {
        $id = $this->get('id', 0);
        
        $log = $this->db()->table('operation_log')->where('id', $id)->find();
        if (!$log) {
            return $this->error('日志不存在');
        }
        
        // 获取管理员信息
        $admin = $this->db()->table('admin')->where('id', $log['admin_id'])->find();
        $log['admin_name'] = $admin['username'] ?? '未知';
        
        // 日志类型
        $types = [
            'login' => '登录',
            'logout' => '退出',
            'create' => '新增',
            'update' => '修改',
            'delete' => '删除',
            'upload' => '上传',
            'download' => '下载',
            'system' => '系统'
        ];
        $log['type_text'] = $types[$log['type']] ?? $log['type'];
        
        $this->assign('log', $log);
        $this->display('log/view');
    }
    
    /**
     * 清空日志
     */
    public function clear() {
        if (!$this->isMethod('POST')) {
            $this->errorRedirect('请求方式错误', '/admin/log');
        }
        
        $days = $this->post('days', 30);
        
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        $result = $this->db()->table('operation_log')
            ->where('created_at', '<', $date)
            ->delete();
        
        // 记录日志
        $this->log('system', '清空' . $days . '天前的操作日志');
        
        $this->successRedirect('已清空 ' . $days . ' 天前的日志', '/admin/log');
    }
    
    /**
     * 删除单条日志
     */
    public function delete() {
        if (!$this->isMethod('POST')) {
            $this->errorRedirect('请求方式错误', '/admin/log');
        }
        
        $id = $this->post('id', 0);
        
        $log = $this->db()->table('operation_log')->where('id', $id)->find();
        if (!$log) {
            $this->errorRedirect('日志不存在', '/admin/log');
        }
        
        $this->db()->table('operation_log')->where('id', $id)->delete();
        
        // 记录日志
        $this->log('delete', '删除操作日志 ID:' . $id);
        
        $this->successRedirect('删除成功', '/admin/log');
    }
    
    /**
     * 记录日志（内部方法）
     */
    protected function log($type, $content, $data = []) {
        $adminId = $_SESSION['admin_id'] ?? 0;
        $admin = $this->db()->table('admin')->where('id', $adminId)->find();
        
        $this->db()->table('operation_log')->insert([
            'admin_id' => $adminId,
            'admin_name' => $admin['username'] ?? 'system',
            'type' => $type,
            'content' => $content,
            'data' => json_encode($data),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
