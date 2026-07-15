<?php
namespace app\controller\home;

use core\Controller;
use app\model\Policy as PolicyModel;

/**
 * 政策法规控制器
 */
class Policy extends Controller {
    
    /**
     * 政策列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $limit = 10;
        
        $policyModel = new PolicyModel();
        $policies = $policyModel->where('status', 1)
                                ->order('publish_date', 'DESC')
                                ->limit(($page - 1) * $limit, $limit)
                                ->select();
        $total = $policyModel->where('status', 1)->count();
        
        $this->assign('policies', $policies);
        $this->assign('page', $page);
        $this->assign('limit', $limit);
        $this->assign('total', $total);
        $this->assign('totalPages', ceil($total / $limit));
        
        $this->fetch('policy/index');
    }
    
    /**
     * 政策详情
     */
    public function detail($id = 0) {
        $id = intval($id);
        if ($id <= 0) {
            $this->error('政策不存在');
            return;
        }
        
        $policyModel = new PolicyModel();
        $policy = $policyModel->where('id', $id)->find();
        
        if (!$policy || $policy['status'] != 1) {
            $this->error('政策不存在或已下架');
            return;
        }
        
        $this->assign('policy', $policy);
        $this->fetch('policy/detail');
    }
}
