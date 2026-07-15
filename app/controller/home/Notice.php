<?php
namespace app\controller\home;

use core\Controller;
use app\model\Notice as NoticeModel;

/**
 * 公告控制器
 */
class Notice extends Controller {
    
    /**
     * 公告列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $limit = 10;
        
        $noticeModel = new NoticeModel();
        $notices = $noticeModel->getList($page, $limit);
        $total = $noticeModel->getTotal();
        
        $this->assign('notices', $notices);
        $this->assign('page', $page);
        $this->assign('limit', $limit);
        $this->assign('total', $total);
        $this->assign('totalPages', ceil($total / $limit));
        
        $this->fetch('notice/index');
    }
    
    /**
     * 公告详情
     */
    public function detail($id = 0) {
        $id = intval($id);
        if ($id <= 0) {
            $this->error('公告不存在');
            return;
        }
        
        $noticeModel = new NoticeModel();
        $notice = $noticeModel->findById($id);
        
        if (!$notice || $notice['status'] != 1) {
            $this->error('公告不存在或已下架');
            return;
        }
        
        // 增加浏览量
        $noticeModel->increaseViews($id);
        
        $this->assign('notice', $notice);
        $this->fetch('notice/detail');
    }
}
