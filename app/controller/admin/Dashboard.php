<?php
namespace app\controller\admin;

use core\Controller;
use app\model\Notice as NoticeModel;
use app\model\Policy as PolicyModel;
use app\model\Judicial as JudicialModel;
use app\model\User as UserModel;
use app\model\Consult as ConsultModel;

/**
 * 后台仪表盘控制器
 */
class Dashboard extends Controller {
    
    /**
     * 后台首页
     */
    public function index() {
        // 统计数据
        $stats = [
            'notice_count' => (new NoticeModel())->count(),
            'policy_count' => (new PolicyModel())->count(),
            'judicial_count' => (new JudicialModel())->count(),
            'user_count' => (new UserModel())->count(),
            'consult_pending' => (new ConsultModel())->where('status', 0)->count()
        ];
        
        // 最新公告
        $latestNotices = (new NoticeModel())->getLatest(5);
        
        // 待处理咨询
        $pendingConsults = (new ConsultModel())->where('status', 0)->order('create_time', 'DESC')->limit(5)->select();
        
        $this->assign([
            'stats' => $stats,
            'latestNotices' => $latestNotices,
            'pendingConsults' => $pendingConsults,
            'admin' => $_SESSION['admin'] ?? []
        ]);
        
        $this->fetch('dashboard/index');
    }
}
