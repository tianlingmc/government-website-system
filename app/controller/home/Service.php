<?php
namespace app\controller\home;

use core\Controller;

/**
 * 在线办事/公众服务控制器
 */
class Service extends Controller {
    
    /**
     * 服务首页
     */
    public function index() {
        $this->fetch('service/index');
    }
    
    /**
     * 办事指南
     */
    public function guide() {
        $this->fetch('service/guide');
    }
    
    /**
     * 在线咨询
     */
    public function consult() {
        $this->fetch('service/consult');
    }
    
    /**
     * 提交咨询
     */
    public function submitConsult() {
        if (!$this->isMethod('POST')) {
            return $this->error('请求方式错误');
        }
        
        $data = [
            'name' => $this->post('name'),
            'phone' => $this->post('phone'),
            'email' => $this->post('email'),
            'title' => $this->post('title'),
            'content' => $this->post('content'),
            'type' => $this->post('type', 1)
        ];
        
        // 验证必填
        if (empty($data['name']) || empty($data['phone']) || empty($data['title']) || empty($data['content'])) {
            return $this->error('请填写必填项');
        }
        
        // 保存咨询
        $consultModel = new \app\model\Consult();
        $consultModel->insert([
            'user_id' => $_SESSION['user_id'] ?? 0,
            'type' => $data['type'],
            'title' => $data['title'],
            'content' => $data['content'],
            'contact_name' => $data['name'],
            'contact_phone' => $data['phone'],
            'contact_email' => $data['email'],
            'status' => 0,
            'create_time' => date('Y-m-d H:i:s')
        ]);
        
        $this->successRedirect('提交成功，我们会尽快回复您', '/service');
    }
}
