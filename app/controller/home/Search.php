<?php
namespace app\controller\home;

use core\Controller;
use app\model\Notice;
use app\model\Policy;
use app\model\Judicial;

/**
 * 全站搜索控制器
 */
class Search extends Controller {
    
    /**
     * 搜索结果
     */
    public function index() {
        $keyword = $this->get('q', '');
        $type = $this->get('type', 'all');
        $page = $this->get('page', 1);
        $limit = 10;
        
        $results = [];
        $total = 0;
        
        if (!empty($keyword)) {
            switch ($type) {
                case 'notice':
                    $noticeModel = new Notice();
                    $results = $noticeModel->search($keyword, $page, $limit);
                    $total = $results['total'];
                    break;
                    
                case 'policy':
                    $policyModel = new Policy();
                    $results = $policyModel->search($keyword, $page, $limit);
                    $total = $results['total'];
                    break;
                    
                case 'judicial':
                    $judicialModel = new Judicial();
                    $results = $judicialModel->search($keyword, $page, $limit);
                    $total = $results['total'];
                    break;
                    
                default:
                    // 搜索所有类型
                    $results = $this->searchAll($keyword, $page, $limit);
                    $total = $results['total'];
                    break;
            }
        }
        
        $this->assign('keyword', $keyword);
        $this->assign('type', $type);
        $this->assign('results', $results);
        $this->assign('total', $total);
        $this->assign('page', $page);
        $this->assign('limit', $limit);
        $this->assign('totalPages', ceil($total / $limit));
        
        $this->fetch('search/index');
    }
    
    /**
     * 搜索所有类型
     */
    private function searchAll($keyword, $page, $limit) {
        $noticeModel = new Notice();
        $policyModel = new Policy();
        $judicialModel = new Judicial();
        
        $notices = $noticeModel->search($keyword, 1, 5);
        $policies = $policyModel->search($keyword, 1, 5);
        $judicials = $judicialModel->search($keyword, 1, 5);
        
        $total = $notices['total'] + $policies['total'] + $judicials['total'];
        
        return [
            'data' => [
                'notices' => $notices['data'],
                'policies' => $policies['data'],
                'judicials' => $judicials['data']
            ],
            'total' => $total,
            'page' => $page,
            'page_size' => $limit,
            'total_page' => ceil($total / $limit)
        ];
    }
}
