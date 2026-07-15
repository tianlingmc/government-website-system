<?php
namespace app\controller\home;

use core\Controller;
use app\model\Judicial as JudicialModel;

/**
 * 裁判文书控制器
 */
class Judicial extends Controller {
    
    /**
     * 文书列表/检索
     */
    public function index() {
        $keyword = $this->get('keyword', '');
        $page = $this->get('page', 1);
        $limit = 10;
        
        $judicialModel = new JudicialModel();
        
        if (!empty($keyword)) {
            // 搜索模式
            $results = $judicialModel->search($keyword, $page, $limit);
            $this->assign('isSearch', true);
            $this->assign('keyword', $keyword);
            $this->assign('judicials', $results['data']);
            $this->assign('total', $results['total']);
            $this->assign('totalPages', $results['total_page']);
        } else {
            // 普通列表
            $judicials = $judicialModel->where('status', 1)
                                      ->order('publish_date', 'DESC')
                                      ->limit(($page - 1) * $limit, $limit)
                                      ->select();
            $total = $judicialModel->where('status', 1)->count();
            
            $this->assign('isSearch', false);
            $this->assign('keyword', '');
            $this->assign('judicials', $judicials);
            $this->assign('total', $total);
            $this->assign('totalPages', ceil($total / $limit));
        }
        
        $this->assign('page', $page);
        $this->assign('limit', $limit);
        
        $this->fetch('judicial/index');
    }
    
    /**
     * 文书详情
     */
    public function detail($id = 0) {
        $id = intval($id);
        if ($id <= 0) {
            $this->error('文书不存在');
            return;
        }
        
        $judicialModel = new JudicialModel();
        $judicial = $judicialModel->where('id', $id)->find();
        
        if (!$judicial || $judicial['status'] != 1) {
            $this->error('文书不存在或未公开');
            return;
        }
        
        // 增加浏览量
        $judicialModel->increaseViews($id);
        
        $this->assign('judicial', $judicial);
        $this->fetch('judicial/detail');
    }
}
