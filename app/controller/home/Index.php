<?php
namespace app\controller\home;

use core\Controller;
use app\model\Notice;
use app\model\Policy;
use app\model\Settings;
use app\model\Language;

/**
 * 首页控制器
 */
class Index extends Controller {
    
    /**
     * 首页
     */
    public function index() {
        // 获取站点配置
        $settingsModel = new Settings();
        $siteConfig = $settingsModel->getAllSettings();
        
        // 获取语言配置
        $langModel = new Language();
        $lang = $langModel->getGroup('common');
        
        // 获取最新公告
        $noticeModel = new Notice();
        $notices = $noticeModel->where('status', 1)
                               ->order('is_top', 'DESC')
                               ->order('publish_time', 'DESC')
                               ->limit(5)
                               ->select();
        
        // 获取最新政策
        $policyModel = new Policy();
        $policies = $policyModel->where('status', 1)
                                ->order('publish_date', 'DESC')
                                ->limit(5)
                                ->select();
        
        $this->assign([
            'siteConfig' => $siteConfig,
            'lang' => $lang,
            'notices' => $notices,
            'policies' => $policies,
            'title' => $siteConfig['site_title'] ?? '政府官网'
        ]);
        
        $this->fetch('index/index');
    }
}
