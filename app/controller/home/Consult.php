<?php
namespace app\controller\home;

use core\Controller;
use app\model\Consult as ConsultModel;

/**
 * 咨询投诉控制器
 */
class Consult extends Controller {
    
    /**
     * 咨询列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $limit = 10;
        
        // 获取已回复的公开咨询
        $consultModel = new ConsultModel();
        $consults = $consultModel->where('status', 2)  // 已回复
                                ->where('is_public', 1)  // 公开
                                ->order('reply_time', 'DESC')
                                ->limit(($page - 1) * $limit, $limit)
                                ->select();
        $total = $consultModel->where('status', 2)->where('is_public', 1)->count();
        
        $this->assign('consults', $consults);
        $this->assign('page', $page);
        $this->assign('limit', $limit);
        $this->assign('total', $total);
        $this->assign('totalPages', ceil($total / $limit));
        
        $this->fetch('consult/index');
    }
    
    /**
     * 提交咨询/投诉页面
     */
    public function submit() {
        // 检查用户是否登录
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }
        
        $this->fetch('consult/submit');
    }
    
    /**
     * 保存咨询/投诉
     */
    public function save() {
        if (!$this->isMethod('POST')) {
            return $this->error('请求方式错误');
        }
        
        // 检查用户是否登录
        if (empty($_SESSION['user_id'])) {
            return $this->error('请先登录');
        }
        
        $title = $this->post('title');
        $content = $this->post('content');
        $type = $this->post('type', 1);
        $isPublic = $this->post('is_public', 1);
        
        // 验证
        if (empty($title) || empty($content)) {
            return $this->error('标题和内容不能为空');
        }
        
        if (strlen($title) > 200) {
            return $this->error('标题长度不能超过200字');
        }
        
        $consultModel = new ConsultModel();
        $id = $consultModel->insert([
            'user_id' => $_SESSION['user_id'],
            'title' => $title,
            'content' => $content,
            'type' => $type,
            'is_public' => $isPublic,
            'status' => 0,  // 待处理
            'create_time' => date('Y-m-d H:i:s')
        ]);
        
        if ($id) {
            $this->successRedirect('提交成功，我们会尽快处理您的咨询', '/consult');
        }
        
        $this->errorRedirect('提交失败，请重试');
    }
    
    /**
     * 我的咨询
     */
    public function my() {
        // 检查用户是否登录
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }
        
        $page = $this->get('page', 1);
        $limit = 10;
        
        $consultModel = new ConsultModel();
        $consults = $consultModel->where('user_id', $_SESSION['user_id'])
                                ->order('create_time', 'DESC')
                                ->limit(($page - 1) * $limit, $limit)
                                ->select();
        $total = $consultModel->where('user_id', $_SESSION['user_id'])->count();
        
        $this->assign('consults', $consults);
        $this->assign('page', $page);
        $this->assign('limit', $limit);
        $this->assign('total', $total);
        $this->assign('totalPages', ceil($total / $limit));
        
        $this->fetch('consult/my');
    }
    
    /**
     * 咨询详情
     */
    public function detail($id = 0) {
        $id = intval($id);
        if ($id <= 0) {
            $this->error('咨询不存在');
            return;
        }
        
        $consultModel = new ConsultModel();
        $consult = $consultModel->where('id', $id)->find();
        
        if (!$consult) {
            $this->error('咨询不存在');
            return;
        }
        
        // 检查权限：公开的或自己的
        if ($consult['is_public'] != 1 && $consult['user_id'] != ($_SESSION['user_id'] ?? 0)) {
            $this->error('您没有权限查看此咨询');
            return;
        }
        
        $this->assign('consult', $consult);
        $this->fetch('consult/detail');
    }
}
